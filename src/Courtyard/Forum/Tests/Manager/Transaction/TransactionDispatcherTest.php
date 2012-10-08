<?php

namespace Courtyard\Forum\Tests\Manager\Transaction;

use Courtyard\Forum\Event\ForumEvent;
use Courtyard\Forum\Manager\Transaction\TransactionEvents;
use Courtyard\Forum\Manager\Transaction\TransactionDispatcher;

class TransactionDispatcherTest extends \PHPUnit_Framework_TestCase
{
    public function testEventsAreDispatched()
    {
        $events = array();
        for ($i = 0; $i < 4; $i++) {
            $events[] = new ForumEvent();
        }

        $eventDispatcher = $this->getMock('Symfony\Component\EventDispatcher\EventDispatcherInterface');
        $eventDispatcher
            ->expects($this->at(0))
            ->method('dispatch')
            ->with('event.before.first', $events[0])
        ;
        $eventDispatcher
            ->expects($this->at(1))
            ->method('dispatch')
            ->with('event.before.second', $events[1])
        ;
        $eventDispatcher
            ->expects($this->at(2))
            ->method('dispatch')
            ->with('event.after.first', $events[2])
        ;

        $eventDispatcher
            ->expects($this->at(3))
            ->method('dispatch')
            ->with('event.after.second', $events[3])
        ;

        $entityManager = $this->getMockBuilder('Doctrine\ORM\EntityManager')
            ->disableOriginalConstructor()
            ->getMock()
        ;
        $entityManager
            ->expects($this->any())
            ->method('getConnection')
            ->will($this->returnValue($this->getConnection()))
        ;


        $transaction = new TransactionEvents();
        $transaction
            ->addFirstPass('event.before.first', $events[0])
            ->addFirstPass('event.before.second', $events[1])
            ->addSecondPass('event.after.first', $events[2])
            ->addSecondPass('event.after.second', $events[3])
        ;

        $dispatcher = new TransactionDispatcher($entityManager, $eventDispatcher);
        $dispatcher->dispatch($transaction);
    }

    public function testEntitiesArePersistedWhenMarked()
    {
        $topic = $this->getMock('Courtyard\Forum\Entity\Topic');
        $post  = $this->getMock('Courtyard\Forum\Entity\Post');

        $eventDispatcher = $this->getMock('Symfony\Component\EventDispatcher\EventDispatcherInterface');

        $event = new ForumEvent();
        $event->addEntityToPersist($topic);
        $event->addEntityToPersist($post);

        $transaction = new TransactionEvents();
        $transaction->addFirstPass('event', $event);

        $entityManager = $this->getEntityManager();
        $entityManager
            ->expects($this->at(1))
            ->method('persist')
            ->with($topic)
        ;
        $entityManager
            ->expects($this->at(2))
            ->method('persist')
            ->with($post)
        ;

        $dispatcher = new TransactionDispatcher($entityManager, $eventDispatcher);
        $dispatcher->dispatch($transaction);
    }

    public function testEntitiesAreRemovedWhenMarked()
    {
        $topic = $this->getMock('Courtyard\Forum\Entity\Topic');
        $post  = $this->getMock('Courtyard\Forum\Entity\Post');

        $eventDispatcher = $this->getMock('Symfony\Component\EventDispatcher\EventDispatcherInterface');

        $event = new ForumEvent();
        $event->addEntityToRemove($topic);
        $event->addEntityToRemove($post);

        $transaction = new TransactionEvents();
        $transaction->addFirstPass('event', $event);

        $entityManager = $this->getEntityManager();
        $entityManager
            ->expects($this->at(1))
            ->method('remove')
            ->with($topic)
        ;
        $entityManager
            ->expects($this->at(2))
            ->method('remove')
            ->with($post)
        ;

        $dispatcher = new TransactionDispatcher($entityManager, $eventDispatcher);
        $dispatcher->dispatch($transaction);
    }

    public function testRollbackOnException()
    {
        $eventDispatcher = $this->getMock('Symfony\Component\EventDispatcher\EventDispatcherInterface');
        $eventDispatcher
            ->expects($this->once())
            ->method('dispatch')
            ->will($this->throwException(new \RuntimeException))
        ;

        $entityManager = $this->getEntityManager();
        $entityManager->getConnection()
            ->expects($this->once())
            ->method('rollBack')
        ;

        $transaction = new TransactionEvents();
        $transaction->addFirstPass('event', new ForumEvent());

        $this->setExpectedException('RuntimeException');

        $dispatcher = new TransactionDispatcher($entityManager, $eventDispatcher);
        $dispatcher->dispatch($transaction);
    }

    protected function getEntityManager()
    {
        $entityManager = $this->getMockBuilder('Doctrine\ORM\EntityManager')
            ->disableOriginalConstructor()
            ->getMock()
        ;

        $entityManager
            ->expects($this->any())
            ->method('getConnection')
            ->will($this->returnValue($this->getConnection()))
        ;

        return $entityManager;
    }

    protected function getConnection()
    {
        return $this->getMock('Doctrine\DBAL\Driver\Connection');
    }
}