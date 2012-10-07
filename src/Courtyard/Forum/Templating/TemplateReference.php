<?php

namespace Courtyard\Forum\Templating;

class TemplateReference
{
    protected $namespace;
    protected $template;

    public function __construct($namespace, $template)
    {
        $this->namespace = $namespace;
        $this->template = $template;
    }

    public function getNamespace()
    {
        return $this->namespace;
    }

    public function getTemplate()
    {
        return $this->template;
    }
}