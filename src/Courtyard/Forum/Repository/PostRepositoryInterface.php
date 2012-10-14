<?php

namespace Courtyard\Forum\Repository;

use Courtyard\Forum\Entity\TopicInterface;

interface PostRepositoryInterface
{
    function find($id);
    function findByTopic(TopicInterface $topic);
}