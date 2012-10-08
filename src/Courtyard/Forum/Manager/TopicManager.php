<?php

namespace Courtyard\Forum\Manager;

use Courtyard\Forum\Entity\BoardInterface;
use Courtyard\Forum\Entity\TopicInterface;
use Courtyard\Forum\Entity\TopicStatuses;
use Courtyard\Forum\Entity\PostStatuses;
use Courtyard\Forum\ForumEvents;
use Courtyard\Forum\Event\PostEvent;
use Courtyard\Forum\Event\TopicEvent;
use Courtyard\Forum\Manager\Transaction\TransactionDispatcher;

class TopicManager implements ObjectManagerInterface
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
     * Creates a new Topic instance for Board
     *
     * @param    Courtyard\Forum\Entity\BoardInterface
     * @return   Courtyard\Forum\Entity\TopicInterface
     */
    public function create($board = null)
    {
        $topic = new $this->class();
        if ($board) {
            $topic->setBoard($board);
        }

        return $topic;
    }

    /**
     * Creates a new Topic
     *
     * @param    Courtyard\Forum\Entity\TopicInterface
     */
    public function persist($topic)
    {
        if (!$topic->getPostFirst()) {
            throw new \InvalidArgumentException('Cannot persist a Topic without a first Post');
        }

        $topic->setStatus(TopicStatuses::STATUS_PUBLISHED);
        $post = $topic->getPostFirst();
        $post->setNumber(1);
        $post->setStatus(postStatuses::STATUS_PUBLISHED);


        $topic->getPostFirst()->setNumber(1);
        $topic->GetPostFirst()->setStatus(PostStatuses::STATUS_PUBLISHED);

        $topicEvent = new TopicEvent($topic);
        $topicEvent->addEntityToPersist($topic);

        $postEvent = new PostEvent($post);
        $postEvent->addEntityToPersist($post);

        $this->dispatcher->dispatch($this->dispatcher->newTransaction()
            ->addFirstPass(ForumEvents::TOPIC_CREATE_PRE, $topicEvent)
            ->addFirstPass(ForumEvents::POST_CREATE_PRE, $postEvent )
            ->addSecondPass(ForumEvents::TOPIC_CREATE_POST, clone $topicEvent)
            ->addSecondPass(ForumEvents::POST_CREATE_POST, clone $postEvent)
        );
    }

    /**
     * Updates an existing Topic
     *
     * @param    Courtyard\Forum\Entity\TopicInterface
     */
    public function update($topic)
    {
        $event = new TopicEvent($topic);
        $event->addEntityToPersist($topic);

        $this->dispatcher->dispatch($this->dispatcher->newTransaction()
            ->addFirstPass(ForumEvents::TOPIC_UPDATE_PRE, $event)
            ->addSecondPass(ForumEvents::TOPIC_UPDATE_POST, clone $event)
        );
    }

    /**
     * Removes a Topic
     *
     * @param    Courtyard\Forum\Entity\TopicInterface
     */
    public function delete($topic)
    {
        $event = new TopicEvent($topic);
        $event->addEntityToRemove($topic);

        $this->dispatcher->dispatch($this->dispatcher->newTransaction()
            ->addFirstPass(ForumEvents::TOPIC_DELETE_PRE, $event)
            ->addSecondPass(ForumEvents::TOPIC_DELETE_POST, clone $event)
        );
    }
}