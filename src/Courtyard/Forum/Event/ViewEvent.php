<?php

namespace Courtyard\Forum\Event;

class ViewEvent
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
    
    public function setTemplate($template)
    {
        $this->template = $template;
    }
    
    public function getVariables()
    {
        return $this->variables;
    }
    
    public function setVariable($key, $value)
    {
        $this->variables[$key] = $value;
    }
    
    public function getEvent()
    {
        return $this->event;
    }
}