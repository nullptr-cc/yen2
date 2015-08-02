<?php

namespace YenTest\Renderer;

use Yen\Renderer\DefaultRenderer;

class DefaultRendererTest extends \PHPUnit_Framework_TestCase
{
    public function testMime()
    {
        $renderer = new DefaultRenderer();
        $this->assertEquals('text/plain', $renderer->mime());
    }

    /**
     * @dataProvider dataRender
     */
    public function testRender($data, $expect)
    {
        $renderer = new DefaultRenderer();
        $this->assertEquals([['Content-Type' => 'text/plain'], $expect], $renderer->render($data));
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
