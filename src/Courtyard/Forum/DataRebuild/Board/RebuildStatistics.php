<?php

namespace Courtyard\Forum\DataRebuild\Board;

class RebuildStatistics
{
    public function addView(Board $board)
    {
        $board->setViews($board->getViews() + 1);
    }

    public function addTopic(Board $board)
    {
        $board->setTopicCount($board->getTopicCount() + 1);
    }

    public function addReply(Board $board)
    {
        $board->setReplyCount($board->getReplyCount() + 1);
    }

    public function rebuild(Board $board)
    {
        // full on query
    }
}