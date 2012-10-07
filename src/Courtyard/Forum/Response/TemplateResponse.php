<?php

namespace Courtyard\Forum\Response;

class TemplateResponse
{
    public function __construct($template, $variables, $event = null)
    {
        $this->template = $template;
        $this->variables = $variables;
        $this->event = $event;
    }
    
    public function getTemplate()
    {
        return $this->template;
    }
    
    public function getVariables()
    {
        return $this->variables;
    }
    
    public function getEvent()
    {
        return $this->event;
    }
}