<?php

namespace Courtyard\Forum\Manager;

use Courtyard\Forum\Event\ForumEvent;
use Doctrine\ORM\EntityManager;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

abstract class TransactionalManager
{
    protected $em;
    protected $dispatcher;

    public function __construct(EntityManager $em, EventDispatcherInterface $dispatcher)
    {
        $this->em = $em;
        $this->dispatcher = $dispatcher;
    }

    protected function newTransaction()
    {
        return new Transaction();
    }

    protected function dispatchTransaction(Transaction $transaction)
    {
        try {
            $this->em->getConnection()->beginTransaction();

            foreach ($transaction->getFirstPass() as $eventName => $event) {
                $this->dispatcher->dispatch($eventName, $event);
            }
            foreach ($transaction->getFirstPass() as $event) {
                $this->applyEventChangeSet($event);
            }


            foreach ($transaction->getSecondPass() as $eventName => $event) {
                $this->dispatcher->dispatch($eventName, $event);
            }
            foreach ($transaction->getSecondPass() as $event) {
                $this->applyEventChangeset($event);
            }

            $this->em->getConnection()->commit();

        } catch (\Exception $e) {
            $this->em->getConnection()->rollBack();
            throw $e;
        }
    }

    protected function applyEventChangeset(ForumEvent $event)
    {
        foreach ($event->getEntitiesToPersist() as $persistEntity) {
            $this->em->persist($persistEntity);
        }
        foreach ($event->getEntitiesToRemove() as $removeEntity) {
            $this->em->remove($removeEntity);
        }

        $this->em->flush();
    }
}