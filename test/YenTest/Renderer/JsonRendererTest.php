<?php

namespace YenTest\Renderer;

use Yen\Renderer\JsonRenderer;

class JsonRendererTest extends \PHPUnit_Framework_TestCase
{
    public function testMime()
    {
        $renderer = new JsonRenderer();
        $this->assertEquals('application/json', $renderer->mime());
    }

    /**
     * @dataProvider dataRender
     */
    public function testRender($data, $expect)
    {
        $renderer = new JsonRenderer();
        $this->assertEquals($expect, $renderer->render($data));
    }

    public function dataRender()
    {
        return [
            [
                [],
                '[]'
            ],
            [
                ['foo' => 'bar'],
                '{"foo":"bar"}'
            ],
            [
                ['foo' => 'bar', 'baz' => 12345],
                '{"foo":"bar","baz":12345}'
            ]
        ];
    }
}
