<?php

namespace Courtyard\Forum\Router;

use Courtyard\Forum\Entity\BoardInterface;
use Courtyard\Forum\Entity\TopicInterface;
use Courtyard\Forum\Entity\PostInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class ForumUrlGenerator implements ForumUrlGeneratorInterface
{
    protected $generator;
    protected $topicsPerPage;
    protected $postsPerPage;

    public function __construct(UrlGeneratorInterface $generator, $topicsPerPage = 25, $postsPerPage = 50)
    {
        $this->generator = $generator;
        $this->topicsPerPage = $topicsPerPage;
        $this->postsPerPage = $postsPerPage;
    }

    public function generateBoardUrl(BoardInterface $board, $absolute = false)
    {
        return $this->generator->generate('forum_view', array(
            'boardSlug' => $board->getSlug()
        ), $absolute);
    }

    public function generateNewTopicUrl(BoardInterface $board, $absolute = false)
    {
        return $this->generator->generate('forum_topic_create', array(
            'boardSlug' => $board->getSlug()
        ), $absolute);
    }

    public function generateTopicUrl(TopicInterface $topic, $absolute = false)
    {
        return $this->generator->generate('forum_topic_view', array(
            'topicId' => $this->generateTopicString($topic),
            'boardSlug' => $topic->getBoard()->getSlug()
        ), $absolute);
    }

    public function generateTopicReplyUrl(TopicInterface $topic, $absolute = false)
    {
        return $this->generator->generate('forum_reply', array(
            'topicId' => $this->generateTopicString($topic),
            'boardSlug' => $topic->getBoard()->getSlug()
        ), $absolute);
    }

    public function generatePostUrl(PostInterface $post, $absolute = false)
    {
        return $this->generateTopicUrl($post->getTopic(), $absolute);
    }

    public function generatePostEditUrl(PostInterface $post, $absolute = false)
    {
        return $this->generator->generate('forum_post_edit', array(
            'postId' => $post->getId()
        ), $absolute);
    }

    protected function generateTopicString(TopicInterface $topic)
    {
        return sprintf('%d-%s', $topic->getId(), $topic->getSlug());
    }
}