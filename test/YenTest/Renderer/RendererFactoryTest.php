<?php

namespace YenTest\Renderer;

use Yen\Renderer\RendererFactory;

class RendererFactoryTest extends \PHPUnit_Framework_TestCase
{
    public function testMake()
    {
        $factory = new RendererFactory();
        $this->assertInstanceOf('\Yen\Renderer\DefaultRenderer', $factory->makeDefaultRenderer());
        $this->assertInstanceOf('\Yen\Renderer\JsonRenderer', $factory->makeJsonRenderer());
        $this->assertInstanceOf('\Yen\Renderer\DefaultRenderer', $factory->makeHtmlRenderer());
    }
}
