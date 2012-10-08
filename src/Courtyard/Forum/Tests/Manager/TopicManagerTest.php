<?php

namespace Courtyard\Forum\Tests\Manager;

use Courtyard\Forum\ForumEvents;
use Courtyard\Forum\Manager\TopicManager;
use Courtyard\Forum\Manager\Transaction\TransactionEvents;

class TopicManagerTest extends TransactionManagerTest
{
    public function testCreateWithCustomClass()
    {
        $dispatcher = $this->getMockBuilder('Courtyard\Forum\Manager\Transaction\TransactionDispatcher')
            ->disableOriginalConstructor()
            ->getMock()
        ;

        $manager = new TopicManager($dispatcher);

        $topic = $this->getMock('Courytard\Forum\Entity\Topic');
        $manager->setClass($class = get_class($topic));

        $this->assertInstanceOf($class, $manager->create());
    }

    public function testPersistThrowsExceptionWithoutFirstPost()
    {
        $dispatcher = $this->getMockBuilder('Courtyard\Forum\Manager\Transaction\TransactionDispatcher')
            ->disableOriginalConstructor()
            ->getMock()
        ;

        $this->setExpectedException('InvalidArgumentException');

        $manager = new TopicManager($dispatcher);
        $manager->persist($this->getMock('Courtyard\Forum\Entity\Topic'));
    }

    public function testPersistTriggersFourEvents()
    {
        $dispatcher = $this->getMockDispatcherExpectingEvents(
            array(ForumEvents::TOPIC_CREATE_PRE, ForumEvents::POST_CREATE_PRE),
            array(ForumEvents::TOPIC_CREATE_POST, ForumEvents::POST_CREATE_POST)
        );

        $topic = $this->getMock('Courtyard\Forum\Entity\Topic');
        $topic
            ->expects($this->any())
            ->method('getPostFirst')
            ->will($this->returnValue($post = $this->getMock('Courtyard\Forum\Entity\Post')))
        ;

        $manager = new TopicManager($dispatcher);
        $manager->persist($topic);
    }


    public function testUpdateTriggersTwoEvents()
    {
        $dispatcher = $this->getMockDispatcherExpectingEvents(
            array(ForumEvents::TOPIC_UPDATE_PRE),
            array(ForumEvents::TOPIC_UPDATE_POST)
        );

        $topic = $this->getMock('Courtyard\Forum\Entity\Topic');

        $manager = new TopicManager($dispatcher);
        $manager->update($topic);
    }

    public function testDeleteTriggersTwoEvents()
    {
        $dispatcher = $this->getMockDispatcherExpectingEvents(
            array(ForumEvents::TOPIC_DELETE_PRE),
            array(ForumEvents::TOPIC_DELETE_POST)
        );

        $manager = new TopicManager($dispatcher);
        $manager->delete($this->getMock('Courtyard\Forum\Entity\Topic'));
    }
}