<?php

namespace Courtyard\Forum\Util\Inflector;

interface SlugInflectorInterface
{
    static function slugify($text);
}