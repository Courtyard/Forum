<?php

namespace Courtyard\Forum\Manager;

use Courtyard\Forum\Entity\BoardInterface;
use Courtyard\Forum\ForumEvents;
use Courtyard\Forum\Event\BoardEvent;

class BoardManager extends TransactionalManager implements ObjectManagerInterface
{
    protected $class;

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

        $this->dispatchTransaction($this->newTransaction()
            ->addFirstPass(ForumEvents::BOARD_CREATE_PRE, $event)
            ->addSecondPass(ForumEvents::BOARD_CREATE_POST, clone $event)
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

        $this->dispatchTransaction($this->newTransaction()
            ->addFirstPass(ForumEvents::BOARD_UPDATE_PRE, $event)
            ->addSecondPass(ForumEvents::BOARD_UPDATE_POST, clone $event)
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

        $this->dispatchTransaction($this->newTransaction()
            ->addFirstPass(ForumEvents::BOARD_DELETE_PRE, $event)
            ->addSecondPass(ForumEvents::BOARD_DELETE_POST, clone $event)
        );
    }
}