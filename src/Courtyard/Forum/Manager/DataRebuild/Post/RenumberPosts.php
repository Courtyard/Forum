<?php

namespace Courtyard\Forum\Manager\DataRebuild\Post;

use Courtyard\Forum\Entity\TopicInterface;
use Courtyard\Forum\Repository\PostRepositoryInterface;

class RenumberPosts
{
    protected $repository;

    public function __construct(PostRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Renumbers all posts within a Topic
     * 
     * @param    TopicInterface        Topic containing posts to renumber
     * @return   PostInterface[]       Posts which were renumbered
     */
    public function renumber(TopicInterface $topic)
    {
        $posts = array();

        $counter = 0;
        foreach ($this->repository->findByTopic($topic) as $post) {
            if ($post->getNumber() != ++$counter) {
                $post->setNumber($counter);
                $posts[] = $post;
            }
        }

        return $posts;
    }
}