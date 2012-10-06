<?php

namespace Courtyard\Forum\Event;

use Courtyard\Forum\Entity\TopicInterface;

class TopicEvent extends ForumEvent
{
    protected $topic;

    public function __construct(TopicInterface $topic)
    {
        $this->topic = $topic;
    }

    public function getTopic()
    {
        return $this->topic;
    }
}