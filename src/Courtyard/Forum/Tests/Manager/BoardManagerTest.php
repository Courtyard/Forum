<?php

namespace Courtyard\Forum\Tests\Manager;

use Courtyard\Forum\ForumEvents;
use Courtyard\Forum\Manager\BoardManager;
use Courtyard\Forum\Manager\Transaction\TransactionEvents;

class BoardManagerTest extends \PHPUnit_Framework_TestCase
{
    public function testCreateWithCustomClass()
    {
        $dispatcher = $this->getMockBuilder('Courtyard\Forum\Manager\Transaction\TransactionDispatcher')
            ->disableOriginalConstructor()
            ->getMock()
        ;

        $manager = new BoardManager($dispatcher);

        $board = $this->getMock('Courytard\Forum\Entity\BoardInterface');
        $manager->setClass($class = get_class($board));

        $this->assertInstanceOf($class, $manager->create());
    }

    public function testPersistTriggersTwoEvents()
    {
        $dispatcher = $this->getMockDispatcherExpectingEvents(
            ForumEvents::BOARD_CREATE_PRE,
            ForumEvents::BOARD_CREATE_POST
        );

        $manager = new BoardManager($dispatcher);
        $manager->persist($this->getMock('Courtyard\Forum\Entity\BoardInterface'));
    }

    public function testUpdateTriggersTwoEvents()
    {
        $dispatcher = $this->getMockDispatcherExpectingEvents(
            ForumEvents::BOARD_UPDATE_PRE,
            ForumEvents::BOARD_UPDATE_POST
        );

        $manager = new BoardManager($dispatcher);
        $manager->persist($this->getMock('Courtyard\Forum\Entity\BoardInterface'));
    }

    public function testDeleteTriggersTwoEvents()
    {
        $dispatcher = $this->getMockDispatcherExpectingEvents(
            ForumEvents::BOARD_DELETE_PRE,
            ForumEvents::BOARD_DELETE_POST
        );

        $manager = new BoardManager($dispatcher);
        $manager->persist($this->getMock('Courtyard\Forum\Entity\BoardInterface'));
    }

    protected function getMockDispatcherExpectingEvents($eventA, $eventB)
    {
        $dispatcher = $this->getMockBuilder('Courtyard\Forum\Manager\Transaction\TransactionDispatcher')
            ->disableOriginalConstructor()
            ->getMock()
        ;
        $dispatcher
            ->expects($this->any())
            ->method('newTransaction')
            ->will($this->returnValue(new TransactionEvents()))
        ;
        $dispatcher
            ->expects($this->any())
            ->method('addFirstPass')
            ->with($this->equalTo($eventA), $this->isInstanceOf('Courtyard\Forum\Event\BoardEvent'))
        ;
        $dispatcher
            ->expects($this->any())
            ->method('addSecondPass')
            ->with($this->equalTo($eventB), $this->isInstanceOf('Courtyard\Forum\Event\BoardEvent'))
        ;

        return $dispatcher;
    }

    // TODO test event
}