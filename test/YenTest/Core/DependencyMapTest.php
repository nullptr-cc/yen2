<?php

namespace YenTest\Core;

use Yen\Core\DependencyMap;

class DependencyMapTest extends \PHPUnit_Framework_TestCase
{
    use \YenMock\MockDC;
    use \YenMock\MockRouter;

    public function testMakeRouter()
    {
        $dc = $this->mockDC();
        $bs = new DependencyMap();
        $this->assertInstanceOf('\Yen\Router\Contract\IRouter', $bs->makeRouter($dc));
    }

    public function testMakeHandlerRegistry()
    {
        $dc = $this->mockDC();
        $bs = new DependencyMap();
        $this->assertInstanceOf('\Yen\Core\FactoryRegistry', $bs->makeHandlerRegistry($dc));
    }

    public function testMakeViewRegistry()
    {
        $dc = $this->mockDC();
        $bs = new DependencyMap();
        $this->assertInstanceOf('\Yen\Core\FactoryRegistry', $bs->makeViewRegistry($dc));
    }

    public function testMakeUrlBuilder()
    {
        $dc = $this->mockDC(['router' => $this->mockRouter()]);
        $bs = new DependencyMap();
        $this->assertInstanceOf('\Yen\Core\UrlBuilder', $bs->makeUrlBuilder($dc));
    }

    public function testget()
    {
        $dc = $this->mockDC(['router' => $this->mockRouter()]);
        $bs = new DependencyMap();
        $cfg = $bs->get();

        $this->assertInternalType('array', $cfg);
        $this->assertCount(4, $cfg);
        $this->assertArrayHasKey('router', $cfg);
        $this->assertTrue(is_callable($cfg['router']));
        $this->assertInstanceOf('\Yen\Router\Contract\IRouter', $cfg['router']($dc));
        $this->assertArrayHasKey('handler_registry', $cfg);
        $this->assertTrue(is_callable($cfg['handler_registry']));
        $this->assertInstanceOf('\Yen\Core\FactoryRegistry', $cfg['handler_registry']($dc));
        $this->assertArrayHasKey('view_registry', $cfg);
        $this->assertTrue(is_callable($cfg['view_registry']));
        $this->assertInstanceOf('\Yen\Core\FactoryRegistry', $cfg['view_registry']($dc));
        $this->assertArrayHasKey('url_builder', $cfg);
        $this->assertTrue(is_callable($cfg['url_builder']));
        $this->assertInstanceOf('\Yen\Core\UrlBuilder', $cfg['url_builder']($dc));
    }
}
