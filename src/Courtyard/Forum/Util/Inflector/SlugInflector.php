<?php

namespace Courtyard\Forum\Util\Inflector;

/**
 * @author Antoine Hérault
 * @link https://github.com/Herzult/HerzultForumBundle/blob/master/Util/Inflector.php
 */
class SlugInflector implements SlugInflectorInterface
{
    /**
     * Create a URL friendly representation from text
     * @param    string        Text to create slug from "`(String)~reprReSeNted###like$$$THIS"
     * @return   string        string-represented-like-this
     */
    static public function slugify($text)
    {
        // replace non letter or digits by -
        $text = preg_replace('~[^\\pL\d]+~u', '-', $text);

        // trim
        $text = trim($text, '-');

        // transliterate
        if (function_exists('iconv')) {
            $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);
        }

        // lowercase
        $text = strtolower($text);

        // remove unwanted characters
        $text = preg_replace('~[^-\w]+~', '', $text);

        return $text ?: "-";
    }
}