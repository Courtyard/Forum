<?php

namespace Courtyard\Forum\Manager;

use Courtyard\Forum\Entity\Topic;
use Courtyard\Forum\Entity\TopicStatuses;
use Courtyard\Forum\Entity\PostStatuses;
use Courtyard\Forum\ForumEvents;
use Courtyard\Forum\Event\PostEvent;
use Courtyard\Forum\Event\TopicEvent;

class TopicManager extends ObjectManager
{
    /**
     * Creates a new Topic
     * @param    \Courtyard\Forum\Entity\Topic
     */
    public function create($topic)
    {
        $this->assertType($topic);
        $topic->setStatus(TopicStatuses::STATUS_PUBLISHED);

        $post = $topic->getPostFirst();
        $post->setNumber(1);
        $post->setStatus(postStatuses::STATUS_PUBLISHED);

        $topic->getPostFirst()->setNumber(1);
        $topic->GetPostFirst()->setStatus(PostStatuses::STATUS_PUBLISHED);

        try {
            $this->em->getConnection()->beginTransaction();

            $this->dispatcher->dispatch(ForumEvents::TOPIC_CREATE_PRE, new TopicEvent($topic));
            $this->dispatcher->dispatch(ForumEvents::POST_CREATE_PRE, new PostEvent($post));

            $this->em->persist($topic);
            $this->em->flush();

            $this->dispatcher->dispatch(ForumEvents::TOPIC_CREATE_POST, new TopicEvent($topic));
            $this->dispatcher->dispatch(ForumEvents::POST_CREATE_POST, new PostEvent($post));

            $this->em->flush();
            $this->em->getConnection()->commit();

        } catch (\Exception $e) {
            $this->em->getConnection()->rollBack();
            throw $e;
        }
    }

    /**
     * Updates an existing Topic
     * @param    \Courtyard\Forum\Entity\Topic
     */
    public function update($topic)
    {
        $this->assertType($topic);

        $this->dispatcher->dispatch(ForumEvents::TOPIC_UPDATE_PRE, new TopicEvent($topic));

        $this->em->persist($topic);
        $this->em->flush();

        $this->dispatcher->dispatch(ForumEvents::TOPIC_UPDATE_POST, new TopicEvent($topic));
    }

    /**
     * Removes a Topic
     * @param    \Courtyard\Forum\Entity\Topic
     */
    public function delete($topic)
    {
        $this->assertType($topic);

        $this->dispatcher->dispatch(ForumEvents::TOPIC_DELETE_PRE, new TopicEvent($topic));

        $this->em->remove($topic);
        $this->em->flush();

        $this->dispatcher->dispatch(ForumEvents::TOPIC_DELETE_POST, new TopicEvent($topic));
    }

    protected function getType()
    {
        return 'Courtyard\Forum\Entity\Topic';
    }
}