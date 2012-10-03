<?php

namespace Courtyard\Forum\Event;

use Courtyard\Forum\Entity\PostInterface;
use Symfony\Component\EventDispatcher\Event;

class PostEvent extends Event
{
    protected $post;
    
    public function __construct(PostInterface $post)
    {
        $this->post = $post;
    }
    
    public function getPost()
    {
        return $this->post;
    }
}