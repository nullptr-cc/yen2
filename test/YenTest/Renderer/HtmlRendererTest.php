<?php

namespace YenTest\Renderer;

use Yen\Renderer\HtmlRenderer;

class HtmlRendererTest extends \PHPUnit_Framework_TestCase
{
    public function testMime()
    {
        $renderer = new HtmlRenderer(null);
        $this->assertEquals('text/html', $renderer->mime());
    }
}
