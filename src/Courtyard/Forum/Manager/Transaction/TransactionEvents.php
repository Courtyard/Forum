<?php

namespace Courtyard\Forum\Manager\Transaction;

use Courtyard\Forum\Event\ForumEvent;

class TransactionEvents
{
    protected $firstEvents;
    protected $secondEvents;

    public function __construct()
    {
        $this->firstEvents = array();
        $this->secondEvents = array();
    }

    public function addFirstPass($eventName, ForumEvent $event)
    {
        $this->firstEvents[$eventName] = $event;
        return $this;
    }

    public function addSecondPass($eventName, ForumEvent $event)
    {
        $this->secondEvents[$eventName] = $event;
        return $this;
    }

    public function getFirstPass()
    {
        return $this->firstEvents;
    }

    public function getSecondPass()
    {
        return $this->secondEvents;
    }
}