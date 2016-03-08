<?php

namespace YenTest\Renderer;

use Yen\Renderer\HtmlRenderer;
use Yen\Settings\Contract\ISettings;

class HtmlRendererTest extends \PHPUnit_Framework_TestCase
{
    public function testMime()
    {
        $settings = $this->getMockForAbstractClass(ISettings::class);

        $renderer = new HtmlRenderer($settings);
        $this->assertEquals('text/html', $renderer->mime());
    }
}
