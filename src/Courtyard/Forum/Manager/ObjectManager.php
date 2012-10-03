<?php

namespace Courtyard\Forum\Manager;

use Doctrine\ORM\EntityManager;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

abstract class ObjectManager implements ObjectManagerInterface
{
    protected $em;
    protected $dispatcher;

    public function __construct(EntityManager $em, EventDispatcherInterface $dispatcher)
    {
        $this->em = $em;
        $this->dispatcher = $dispatcher;
    }

    protected function assertType($obj)
    {
        $type = $this->getType();
        if (!$obj instanceof $type) {
            throw new \InvalidArgumentException(sprintf(
                '%s expects objects to be of type "%s", but "%s" given',
                get_class($this),
                $this->getType(),
                get_class($obj)
            ));
        }
    }

    abstract protected function getType();
}