<?php

namespace Courtyard\Forum\Repository;

use Courtyard\Forum\Entity\BoardInterface;

interface TopicRepositoryInterface
{
    function find($id);
    function findByBoard(BoardInterface $board);
}