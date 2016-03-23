<?php

namespace YenTest\Renderer;

use Yen\Renderer\TextRenderer;

class TextRendererTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider dataRender
     */
    public function testRender($data, $expect)
    {
        $renderer = new TextRenderer();
        $doc = $renderer->render($data);
        $this->assertEquals('text/plain', $doc->mime());
        $this->assertEquals($expect, $doc->content());
    }

    public function dataRender()
    {
        return [
            [
                [],
                "Array\n(\n)\n"
            ],
            [
                ['foo' => 'bar'],
                "Array\n(\n    [foo] => bar\n)\n"
            ],
            [
                ['foo' => 'bar', 'baz' => 12345],
                "Array\n(\n    [foo] => bar\n    [baz] => 12345\n)\n"
            ],
        ];
    }
}
