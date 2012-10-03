<?php

namespace Courtyard\Forum\Event;

use Courtyard\Forum\Entity\TopicInterface;
use Symfony\Component\EventDispatcher\Event;

class TopicEvent extends Event
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