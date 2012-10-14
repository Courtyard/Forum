<?php

namespace Courtyard\Forum\Manager\DataRebuild\Topic;

class FirstLast
{
    public function onRemovePost(PostInterface $post, TopicInterface $topic)
    {
        if ($topic->getPostFirst() == $post) {
            $topic->setPostFirst(null);
        }
    }
}