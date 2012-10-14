<?php

namespace Courtyard\Forum\Router;

use Courtyard\Forum\Entity\BoardInterface;
use Courtyard\Forum\Entity\TopicInterface;
use Courtyard\Forum\Entity\PostInterface;

interface ForumUrlGeneratorInterface
{
    function generateBoardUrl(BoardInterface $board, $absolute = false);

    function generateNewTopicUrl(BoardInterface $board, $absolute = false);

    function generateTopicUrl(TopicInterface $topic, $absolute = false);

    function generateTopicReplyUrl(TopicInterface $topic, $absolute = false);

    function generatePostUrl(PostInterface $post, $absolute = false);

    function generatePostEditUrl(PostInterface $post, $absolute = false);
}