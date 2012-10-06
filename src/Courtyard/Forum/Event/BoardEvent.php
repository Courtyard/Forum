<?php

namespace Courtyard\Forum\Event;

use Courtyard\Forum\Entity\BoardInterface;

class BoardEvent extends ForumEvent
{
    protected $board;

    public function __construct(BoardInterface $board)
    {
        $this->board = $board;
    }

    public function getBoard()
    {
        return $this->board;
    }
}