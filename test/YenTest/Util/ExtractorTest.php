<?php

namespace YenTest\Util;

use Yen\Util\Extractor;

class ExtractorTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider dataForExtractInt
     */
    public function testExtractInt($data, $key, $expect)
    {
        $value = Extractor::extractInt($data, $key);
        $this->assertEquals($expect, $value);
    }

    public function dataForExtractInt()
    {
        return [
            [
                ['foo' => 'bar', 'baz' => 1], 'foo', 0
            ],
            [
                ['foo' => 'bar', 'baz' => 1], 'baz', 1
            ],
        ];
    }

    /**
     * @dataProvider dataForExtractString
     */
    public function testExtractString($data, $key, $expect)
    {
        $value = Extractor::extractString($data, $key);
        $this->assertEquals($expect, $value);
    }

    public function dataForExtractString()
    {
        return [
            [
                ['foo' => 'bar', 'baz' => 1], 'foo', 'bar'
            ],
            [
                ['foo' => 'bar', 'baz' => 1], 'baz', ''
            ],
        ];
    }

    /**
     * @dataProvider dataForExtractArray
     */
    public function testExtractArray($data, $key, $expect)
    {
        $value = Extractor::extractArray($data, $key);
        $this->assertEquals($expect, $value);
    }

    public function dataForExtractArray()
    {
        return [
            [
                ['foo' => 'bar', 'baz' => [1,2,3]], 'foo', ['bar']
            ],
            [
                ['foo' => 'bar', 'baz' => [1,2,3]], 'baz', [1,2,3]
            ],
            [
                ['foo' => 'bar', 'baz' => [1,2,3]], 'bom', []
            ],
        ];
    }
}
