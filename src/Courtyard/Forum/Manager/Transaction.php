<?php

namespace Courtyard\Forum\Manager;

use Courtyard\Forum\Event\ForumEvent;

class Transaction
{
    protected $firstDispatches;
    protected $secondDispatches;

    public function __construct()
    {
        $this->firstDispatches = array();
        $this->secondDispatches = array();
    }

    public function addFirstPass($eventName, ForumEvent $event)
    {
        $this->firstDispatches[$eventName] = $event;
        return $this;
    }

    public function addSecondPass($eventName, ForumEvent $event)
    {
        $this->secondDispatches[$eventName] = $event;
        return $this;
    }

    public function getFirstPass()
    {
        return $this->firstDispatches;
    }

    public function getSecondPass()
    {
        return $this->secondDispatches;
    }
}