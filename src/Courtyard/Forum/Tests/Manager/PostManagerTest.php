<?php

namespace Courtyard\Forum\Tests\Manager;

use Courtyard\Forum\ForumEvents;
use Courtyard\Forum\Manager\PostManager;

class PostManagerTest extends TransactionManagerTest
{
    public function testCreateWithCustomClass()
    {
        $dispatcher = $this->getMockBuilder('Courtyard\Forum\Manager\Transaction\TransactionDispatcher')
            ->disableOriginalConstructor()
            ->getMock()
        ;
        $post = $this->getMock('Courytard\Forum\Entity\Post');

        $manager = new PostManager($dispatcher);
        $manager->setClass($class = get_class($post));

        $this->assertInstanceOf($class, $manager->create());
    }

    public function testPersistThrowsExceptionWithoutTopic()
    {
        $dispatcher = $this->getMockBuilder('Courtyard\Forum\Manager\Transaction\TransactionDispatcher')
            ->disableOriginalConstructor()
            ->getMock()
        ;

        $this->setExpectedException('InvalidArgumentException');

        $manager = new PostManager($dispatcher);
        $manager->persist($this->getMock('Courtyard\Forum\Entity\Post'));
    }

    public function testPersistTriggersTwoEvents()
    {
        $dispatcher = $this->getMockDispatcherExpectingEvents(
            array(ForumEvents::POST_CREATE_PRE),
            array(ForumEvents::POST_CREATE_POST)
        );

        $topic = $this->getMock('Courtyard\Forum\Entity\Topic');
        $post = $this->getMock('Courtyard\Forum\Entity\Post');

        $post
            ->expects($this->any())
            ->method('getTopic')
            ->will($this->returnValue($topic))
        ;

        $lastPost = $this->getMock('Courtyard\Forum\Entity\Post');
        $lastPost
            ->expects($this->any())
            ->method('getTopic')
            ->will($this->returnValue($topic))
        ;
        $lastPost
            ->expects($this->any())
            ->method('getNumber')
            ->will($this->returnValue(1))
        ;

        $topic
            ->expects($this->any())
            ->method('getPostLast')
            ->will($this->returnValue($lastPost))
        ;

        $manager = new PostManager($dispatcher);
        $manager->persist($post);
    }


    public function testUpdateTriggersTwoEvents()
    {
        $dispatcher = $this->getMockDispatcherExpectingEvents(
            array(ForumEvents::POST_UPDATE_PRE),
            array(ForumEvents::POST_UPDATE_POST)
        );

        $post = $this->getMock('Courtyard\Forum\Entity\Post');

        $manager = new PostManager($dispatcher);
        $manager->update($post);
    }

    public function testDeleteTriggersTwoEvents()
    {
        $dispatcher = $this->getMockDispatcherExpectingEvents(
            array(ForumEvents::POST_DELETE_PRE),
            array(ForumEvents::POST_DELETE_POST)
        );

        $manager = new PostManager($dispatcher);
        $manager->delete($this->getMock('Courtyard\Forum\Entity\Post'));
    }

    public function testDeletingFirstPostThrowsException()
    {
        $post = $this->getMock('Courtyard\Forum\Entity\Post');
        $post
            ->expects($this->once())
            ->method('getNumber')
            ->will($this->returnValue(1))
        ;

        $dispatcher = $this->getMockBuilder('Courtyard\Forum\Manager\Transaction\TransactionDispatcher')
            ->disableOriginalConstructor()
            ->getMock()
        ;

        $this->setExpectedException('BadMethodCallException');

        $manager = new PostManager($dispatcher);
        $manager->delete($post);
    }
}