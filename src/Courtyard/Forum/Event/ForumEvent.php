<?php

namespace Courtyard\Forum\Event;

use Symfony\Component\EventDispatcher\Event;

class ForumEvent extends Event
{
    protected $persistEntities = array();
    protected $removeEntities = array();

    public function addEntityToPersist($obj)
    {
        $this->persistEntities[$obj->getId() ?: spl_object_hash($obj)] = $obj;
    }

    public function getEntitiesToPersist()
    {
        return $this->persistEntities;
    }

    public function addEntityToRemove($obj)
    {
        $this->removeEntities[$obj->getId() ?: spl_object_hash($obj)] = $obj;
    }

    public function getEntitiesToRemove()
    {
        return $this->removeEntities;
    }
}