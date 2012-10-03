<?php

namespace Courtyard\Forum\Entity;

final class PostStatuses
{
    const STATUS_DRAFT = 0;
    const STATUS_PUBLISHED = 1;
    const STATUS_MODERATED = 2;
    const STATUS_DELETED = 3;

    static public function getStatuses()
    {
        return array(
            self::STATUS_DRAFT,
            self::STATUS_PUBLISHED,
            self::STATUS_MODERATED,
            self::STATUS_DELETED
        );
    }
}