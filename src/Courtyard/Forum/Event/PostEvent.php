<?php

namespace Courtyard\Forum\Event;

use Courtyard\Forum\Entity\PostInterface;

class PostEvent extends ForumEvent
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