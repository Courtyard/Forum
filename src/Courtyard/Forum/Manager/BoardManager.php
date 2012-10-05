<?php

namespace Courtyard\Forum\Manager;

use Courtyard\Forum\Entity\BoardInterface;
use Courtyard\Forum\ForumEvents;
use Courtyard\Forum\Event\BoardEvent;

class BoardManager extends ObjectManager
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
    public function createNew()
    {
        return new $this->class();
    }

    /**
     * Creates a new Board
     * @param    Courtyard\Forum\Entity\BoardInterface
     */
    public function create($board)
    {
        $this->assertType($board);

        try {
            $this->em->getConnection()->beginTransaction();

            $this->dispatcher->dispatch(ForumEvents::BOARD_CREATE_PRE, new BoardEvent($board));

            $this->em->persist($board);
            $this->em->flush();

            $this->dispatcher->dispatch(ForumEvents::BOARD_CREATE_POST, new BoardEvent($board));

            $this->em->flush();
            $this->em->getConnection()->commit();

        } catch (\Exception $e) {
            $this->em->getConnection()->rollBack();
            throw $e;
        }
    }

    /**
     * Updates an existing Board
     * @param    Courtyard\Forum\Entity\BoardInterface
     */
    public function update($board)
    {
        $this->assertType($board);

        $this->dispatcher->dispatch(ForumEvents::BOARD_UPDATE_PRE, new BoardEvent($board));

        $this->em->persist($board);
        $this->em->flush();

        $this->dispatcher->dispatch(ForumEvents::BOARD_UPDATE_POST, new BoardEvent($board));
    }

    /**
     * Removes a Board
     * @param    Courtyard\Forum\Entity\BoardInterface
     */
    public function delete($board)
    {
        $this->assertType($board);

        $this->dispatcher->dispatch(ForumEvents::BOARD_DELETE_PRE, new BoardEvent($board));

        $this->em->remove($board);
        $this->em->flush();

        $this->dispatcher->dispatch(ForumEvents::BOARD_DELETE_POST, new BoardEvent($board));
    }

    protected function getType()
    {
        return 'Courtyard\Forum\Entity\BoardInterface';
    }
}