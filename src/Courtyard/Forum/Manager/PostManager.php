<?php

namespace Courtyard\Forum\Manager;

use Courtyard\Forum\Entity\TopicInterface;
use Courtyard\Forum\Entity\PostStatuses;
use Courtyard\Forum\ForumEvents;
use Courtyard\Forum\Event\PostEvent;

class PostManager extends TransactionalManager implements ObjectManagerInterface
{
    protected $class;

    public function setClass($class)
    {
        $this->class = $class;
    }

    /**
     * Creates a new Post instance for Topic
     * 
     * @param    Courtyard\Forum\Entity\TopicInterface
     * @return   Courtyard\Forum\Entity\PostInterface
     */
    public function create($topic = null)
    {
        $post = new $this->class();
        $post->setTopic($topic);
        // $topic->addPost()?

        return $post;
    }
    
    /**
     * Persists a new Post
     * @param    Courtyard\Forum\Entity\PostInterface
     */
    public function persist($post)
    {
        $post->setStatus(PostStatuses::STATUS_PUBLISHED);
        $post->setNumber($post->getTopic()->getPostLast()->getNumber() + 1);

        $post->getTopic()->addPost($post);
        
        $postEvent = new PostEvent($post);
        $postEvent->addEntityToPersist($post);

        $this->dispatchTransaction($this->newTransaction()
            ->addFirstPass(ForumEvents::POST_CREATE_PRE, $postEvent)
            ->addSecondPass(ForumEvents::POST_CREATE_POST, clone $postEvent)
        );
    }

    /**
     * Updates an existing Post
     * @param    Courtyard\Forum\Entity\PostInterface
     */
    public function update($post)
    {
        $postEvent = new PostEvent($post);
        $postEvent->addEntityToPersist($post);

        $this->dispatchTransaction($this->newTransaction()
            ->addFirstPass(ForumEvents::POST_UPDATE_PRE, $postEvent)
            ->addSecondPass(ForumEvents::POST_UPDATE_POST, clone $postEvent)
        );
    }

    /**
     * Removes a Post
     * @param    Courtyard\Forum\Entity\PostInterface
     */
    public function delete($post)
    {
        $postEvent = new PostEvent($post);
        $postEvent->addEntityToRemove($post);

        $this->dispatchTransaction($this->newTransaction()
            ->addFirstPass(ForumEvents::POST_DELETE_PRE, $postEvent)
            ->addSecondPass(ForumEvents::POST_DELETE_POST, clone $postEvent)
        );
    }
}