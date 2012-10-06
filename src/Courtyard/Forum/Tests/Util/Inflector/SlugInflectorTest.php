<?php

namespace Courtyard\Forum\Tests\Util\Inflector;

use Courtyard\Forum\Util\Inflector\SlugInflector;

class SlugInflectorTest extends \PHPUnit_Framework_TestCase
{
    public function testRemovesNonAlphaNumeric()
    {
        $this->runTests(array(
            'remove space' => 'remove-space',
            '#$@#$#@$@m#' => 'm',
            '(String)~repReSeNted###like$$$THIS' => 'string-represented-like-this'
        ));
    }

    public function testRemovesOuterDashes()
    {
        $this->runTests(array(
            ' -something- ' => 'something',
            '#!%3~yes^ ^ ' => '3-yes'
        ));
    }

    public function testConvertsToLower()
    {
        $this->runTests(array(
            'Title of Something' => 'title-of-something',
            'CAPITAL' => 'capital'
        ));
    }

    public function testRemovesBadCharacters()
    {
        $this->runTests(array(
            "\r\n dfsdfsd \t" => 'dfsdfsd',
            "\0hello\0" => 'hello'
        ));
    }
    
    public function testTransliterates()
    {
        if (!function_exists('iconv')) {
            $this->markTestSkipped();
        }

        $this->runTests(array(
            'ABBĀSĀBĀD' => 'abbasabad'
        ));
    }
    
    public function testEmptyYieldsDash()
    {
        $this->runTests(array(
            '' => '-'
        ));
    }

    protected function runTests($data)
    {
        foreach ($data as $input => $expected) {
            $this->assertEquals($expected, SlugInflector::slugify($input));
        }
    }
}