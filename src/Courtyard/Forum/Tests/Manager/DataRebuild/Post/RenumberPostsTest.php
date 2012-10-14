<?php

namespace Courtyard\Forum\Tests\Manager\DataRebuild\Post;

use Courtyard\Forum\Entity\Post;
use Courtyard\Forum\Manager\DataRebuild\Post\RenumberPosts;

class RenumberPostsTest extends \PHPUnit_Framework_TestCase
{
    public function testRenumberPosts()
    {
        $topic = $this->getMock('Courtyard\Forum\Entity\Topic');

        $repository = $this->getMock('Courtyard\Forum\Repository\PostRepositoryInterface');
        $repository
            ->expects($this->once())
            ->method('findByTopic')
            ->with($topic)
            ->will($this->returnValue($this->getPosts(array(0, 0))))
        ;

        $rebuilder = new RenumberPosts($repository);
        $posts = $rebuilder->renumber($topic);

        $this->assertEquals(1, $posts[0]->getNumber());
        $this->assertEquals(2, $posts[1]->getNumber());
    }

    public function testReturnsOnlyModified()
    {
        $topic = $this->getMock('Courtyard\Forum\Entity\Topic');

        $repository = $this->getMock('Courtyard\Forum\Repository\PostRepositoryInterface');
        $repository
            ->expects($this->once())
            ->method('findByTopic')
            ->with($topic)
            ->will($this->returnValue($inPosts = $this->getPosts(array(1, 2, 5))))
        ;

        $rebuilder = new RenumberPosts($repository);
        $posts = $rebuilder->renumber($topic);

        $this->assertCount(1, $posts);
        $this->assertSame($posts[0], $inPosts[2]);
    }

    protected function getPosts(array $numbers)
    {
        $posts = array();
        foreach ($numbers as $number) {
            $post = new Post();
            $post->setNumber($number);

            $posts[] = $post;
        }

        return $posts;
    }
}