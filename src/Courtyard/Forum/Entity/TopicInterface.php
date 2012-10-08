<?php

namespace Courtyard\Forum\Entity;

interface TopicInterface
{
    function setBoard(BoardInterface $board);
    function getBoard();
    function getPosts();
}