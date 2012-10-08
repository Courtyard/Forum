<?php

namespace Courtyard\Forum\Manager;

use Courtyard\Forum\Entity\TopicInterface;
use Courtyard\Forum\Entity\PostStatuses;
use Courtyard\Forum\ForumEvents;
use Courtyard\Forum\Event\PostEvent;
use Courtyard\Forum\Manager\Transaction\TransactionDispatcher;

class PostManager implements ObjectManagerInterface
{
    protected $dispatcher;
    protected $class;

    public function __construct(TransactionDispatcher $dispatcher)
    {
        $this->dispatcher = $dispatcher;
    }

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

        if ($topic) {
            $post->setTopic($topic);
        }
        // $topic->addPost()?

        return $post;
    }

    /**
     * Persists a new Post
     * @param    Courtyard\Forum\Entity\PostInterface
     */
    public function persist($post)
    {
        if (!$post->getTopic()) {
            throw new \InvalidArgumentException('Post cannot be persisted without a Topic');
        }
        if (!$post->getTopic()->getPostLast()) {
            throw new \InvalidArgumentExecption('Post\'s Topic has no last post defined');
        }

        $post->setStatus(PostStatuses::STATUS_PUBLISHED);
        $post->setNumber($post->getTopic()->getPostLast()->getNumber() + 1);

        $post->getTopic()->addPost($post);

        $postEvent = new PostEvent($post);
        $postEvent->addEntityToPersist($post);

        $this->dispatcher->dispatch($this->dispatcher->newTransaction()
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

        $this->dispatcher->dispatch($this->dispatcher->newTransaction()
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

        $this->dispatcher->dispatch($this->dispatcher->newTransaction()
            ->addFirstPass(ForumEvents::POST_DELETE_PRE, $postEvent)
            ->addSecondPass(ForumEvents::POST_DELETE_POST, clone $postEvent)
        );
    }
}