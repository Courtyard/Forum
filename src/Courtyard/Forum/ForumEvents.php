<?php

namespace Courtyard\Forum;

final class ForumEvents
{
    const BOARD_CREATE_PRE = 'board.create.pre';
    const BOARD_CREATE_POST = 'board.create.post';

    const BOARD_UPDATE_PRE = 'board.update.pre';
    const BOARD_UPDATE_POST = 'board.update.post';

    const BOARD_DELETE_PRE = 'board.delete.pre';
    const BOARD_DELETE_POST = 'board.delete.post';


    const TOPIC_CREATE_PRE = 'topic.create.pre';
    const TOPIC_CREATE_POST = 'topic.create.post';

    const TOPIC_UPDATE_PRE = 'topic.update.pre';
    const TOPIC_UPDATE_POST = 'topic.update.post';

    const TOPIC_DELETE_PRE = 'topic.delete.pre';
    const TOPIC_DELETE_POST = 'topic.delete.post';


    const POST_CREATE_PRE = 'post.create.pre';
    const POST_CREATE_POST = 'post.create.post';

    const POST_UPDATE_PRE = 'post.update.pre';
    const POST_UPDATE_POST = 'post.update.post';

    const POST_DELETE_PRE = 'post.delete.pre';
    const POST_DELETE_POST = 'post.delete.post';
}