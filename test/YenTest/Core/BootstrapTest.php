<?php

namespace YenTest\Core;

use Yen\Core\Bootstrap;

class BootstrapTest extends \PHPUnit_Framework_TestCase
{
    use \YenTest\Mixin\MockDC;
    use \YenTest\Mixin\MockRouter;

    public function testInitRouter()
    {
        $dc = $this->mockDC();
        $bs = new Bootstrap();
        $this->assertInstanceOf('\Yen\Router\Contract\IRouter', $bs->initRouter($dc));
    }

    public function testInitHandlerFactory()
    {
        $dc = $this->mockDC();
        $bs = new Bootstrap();
        $this->assertInstanceOf('\Yen\Handler\HandlerFactory', $bs->initHandlerFactory($dc));
    }

    public function testInitViewFactory()
    {
        $dc = $this->mockDC();
        $bs = new Bootstrap();
        $this->assertInstanceOf('\Yen\View\ViewFactory', $bs->initViewFactory($dc));
    }

    public function testInitRendererFactory()
    {
        $dc = $this->mockDC();
        $bs = new Bootstrap();
        $this->assertInstanceOf('\Yen\Renderer\RendererFactory', $bs->initRendererFactory($dc));
    }

    public function testInitUrlBuilder()
    {
        $dc = $this->mockDC(['router' => $this->mockRouter()]);
        $bs = new Bootstrap();
        $this->assertInstanceOf('\Yen\Core\UrlBuilder', $bs->initUrlBuilder($dc));
    }

    public function testBootstrap()
    {
        $dc = $this->mockDC(['router' => $this->mockRouter()]);
        $bs = new Bootstrap();
        $cfg = $bs->bootstrap();

        $this->assertInternalType('array', $cfg);
        $this->assertCount(5, $cfg);
        $this->assertArrayHasKey('router', $cfg);
        $this->assertTrue(is_callable($cfg['router']));
        $this->assertInstanceOf('\Yen\Router\Contract\IRouter', $cfg['router']($dc));
        $this->assertArrayHasKey('handler_factory', $cfg);
        $this->assertTrue(is_callable($cfg['handler_factory']));
        $this->assertInstanceOf('\Yen\Handler\HandlerFactory', $cfg['handler_factory']($dc));
        $this->assertArrayHasKey('view_factory', $cfg);
        $this->assertTrue(is_callable($cfg['view_factory']));
        $this->assertInstanceOf('\Yen\View\ViewFactory', $cfg['view_factory']($dc));
        $this->assertArrayHasKey('renderer_factory', $cfg);
        $this->assertTrue(is_callable($cfg['renderer_factory']));
        $this->assertInstanceOf('\Yen\Renderer\RendererFactory', $cfg['renderer_factory']($dc));
        $this->assertArrayHasKey('url_builder', $cfg);
        $this->assertTrue(is_callable($cfg['url_builder']));
        $this->assertInstanceOf('\Yen\Core\UrlBuilder', $cfg['url_builder']($dc));
    }
}
