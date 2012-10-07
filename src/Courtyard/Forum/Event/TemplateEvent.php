<?php

namespace Courtyard\Forum\Event;

use Courtyard\Forum\Templating\TemplateResponse;
use Symfony\Component\EventDispatcher\Event;

class TemplateEvent extends Event
{
    protected $response;

    public function __construct(TemplateResponse $response)
    {
        $this->response = $response;
    }

    public function getResponse()
    {
        return $this->response;
    }
}