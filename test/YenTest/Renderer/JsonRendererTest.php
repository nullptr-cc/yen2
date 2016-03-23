<?php

namespace YenTest\Renderer;

use Yen\Renderer\JsonRenderer;

class JsonRendererTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider dataRender
     */
    public function testRender($data, $expect)
    {
        $renderer = new JsonRenderer();
        $doc = $renderer->render($data);
        $this->assertEquals('application/json', $doc->mime());
        $this->assertEquals($expect, $doc->content());
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
