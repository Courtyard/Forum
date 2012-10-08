<?php

namespace Courtyard\Forum\Manager;

use Courtyard\Forum\Entity\BoardInterface;
use Courtyard\Forum\ForumEvents;
use Courtyard\Forum\Event\BoardEvent;
use Courtyard\Forum\Manager\Transaction\TransactionDispatcher;

class BoardManager implements ObjectManagerInterface
{
    protected $dispatcher;
    protected $class;

    public function __construct(TransactionDispatcher $dispatcher)
    {
        $this->dispatcher = $dispatcher;
    }

    public function setClass($class)
    {
        $this->class = $class;
    }

    /**
     * Creates a new Board
     * @return   Courtyard\Forum\Entity\BoardInterface
     */
    public function create($obj = null)
    {
        return new $this->class();
    }

    /**
     * Persists a Board
     * @param    Courtyard\Forum\Entity\BoardInterface
     */
    public function persist($board)
    {
        $event = new BoardEvent($board);
        $event->addEntityToPersist($board);

        $this->dispatcher->dispatch($this->dispatcher->newTransaction()
            ->addFirstPass(ForumEvents::BOARD_CREATE_PRE, $event)
            ->addSecondPass(ForumEvents::BOARD_CREATE_POST, new BoardEvent($board))
        );
    }

    /**
     * Updates an existing Board
     * @param    Courtyard\Forum\Entity\BoardInterface
     */
    public function update($board)
    {
        $event = new BoardEvent($board);
        $event->addEntityToPersist($board);

        $this->dispatcher->dispatch($this->dispatcher->newTransaction()
            ->addFirstPass(ForumEvents::BOARD_UPDATE_PRE, $event)
            ->addSecondPass(ForumEvents::BOARD_UPDATE_POST, new BoardEvent($board))
        );
    }

    /**
     * Removes a Board
     * @param    Courtyard\Forum\Entity\BoardInterface
     */
    public function delete($board)
    {
        $event = new BoardEvent($board);
        $event->addEntityToRemove($board);

        $this->dispatcher->dispatch($this->dispatcher->newTransaction()
            ->addFirstPass(ForumEvents::BOARD_DELETE_PRE, $event)
            ->addSecondPass(ForumEvents::BOARD_DELETE_POST, new BoardEvent($board))
        );
    }
}