<?php

namespace Courtyard\Forum\DataRebuild\Board;

class RebuildLatestTopic
{
    public function setLatestTopic(Topic $topic, Board $board)
    {
        $board->setLastTopic($topic);
        // internally... bump date, etc
    }
}