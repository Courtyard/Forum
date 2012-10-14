<?php

namespace Courtyard\Forum\Manager\Transaction;

use Courtyard\Forum\Event\ForumEvent;
use Doctrine\ORM\EntityManager;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

/**
 * The Transaction Dispatcher runs a two-pass transaction.
 *
 * During each pass, all of the added events are triggered, and then any
 * entities marked as requiring persistence or removal or processed.
 *
 * This gives developers the chance to change data before the changes are made,
 * and also run any necessary cleanup code afterward.
 *
 * <code>
 *
 *     $event = new ForumEvent($entity);
 *     $event->addEntitiyToPersist($entity);
 *
 *     $dispatcher->dispatch($dispatcher->newTransaction()
 *         ->addFirstPass('some.event.before', $event)
 *         ->addSecondPass('some.event.after', clone $event)
 *     );
 * </code>
 *
 * The above will run some.event.before, save any changes marked in $event (1)
 * and then run some.event.after, and save any other changes marked $event (2)
 *
 */
class TransactionDispatcher
{
    protected $em;
    protected $dispatcher;

    public function __construct(EntityManager $em, EventDispatcherInterface $dispatcher)
    {
        $this->em = $em;
        $this->dispatcher = $dispatcher;
    }

    /**
     * Shortcut for a new TransactionEvents object
     * @return    TransactionEvents
     */
    public function newTransaction()
    {
        return new TransactionEvents();
    }

    /**
     * Dispatches the TransactionEvents in two passes
     * @param    TransactionEvents
     */
    public function dispatch(TransactionEvents $transaction)
    {
        try {
            $this->em->getConnection()->beginTransaction();

            foreach ($transaction->getFirstPass() as $eventName => $event) {
                $this->dispatcher->dispatch($eventName, $event);
            }
            foreach ($transaction->getFirstPass() as $eventName => $event) {
                $this->applyEventChangeSet($event);
            }

            $this->em->flush();

            foreach ($transaction->getSecondPass() as $eventName => $event) {
                $this->dispatcher->dispatch($eventName, $event);
            }
            foreach ($transaction->getSecondPass() as $eventName => $event) {
                $this->applyEventChangeSet($event);
            }

            $this->em->flush();
            $this->em->getConnection()->commit();

        } catch (\Exception $e) {
            $this->em->getConnection()->rollBack();
            throw $e;
        }
    }

    /**
     * Applies changes from a ForumEvent changeset to the entity manager
     * @param    Courtyard\Forum\Event\ForumEvent
     */
    protected function applyEventChangeset(ForumEvent $event)
    {
        foreach ($event->getEntitiesToPersist() as $persistEntity) {
            $this->em->persist($persistEntity);
        }
        foreach ($event->getEntitiesToRemove() as $removeEntity) {
            $this->em->remove($removeEntity);
        }
    }
}