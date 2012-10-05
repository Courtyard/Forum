<?php

namespace Courtyard\Forum\Event;

use Courtyard\Forum\Entity\BoardInterface;
use Symfony\Component\EventDispatcher\Event;

class BoardEvent extends Event
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