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

    public function testHas()
    {
        $dep_map = new DependencyMap();

        $this->assertTrue($dep_map->has('router'));
        $this->assertTrue($dep_map->has('handler_registry'));
        $this->assertTrue($dep_map->has('view_registry'));
        $this->assertTrue($dep_map->has('url_builder'));
        $this->assertFalse($dep_map->has('foo'));
    }

    public function testGet()
    {
        $dep_map = new DependencyMap();

        $this->assertTrue(is_callable($dep_map->get('router')));
        $this->assertTrue(is_callable($dep_map->get('handler_registry')));
        $this->assertTrue(is_callable($dep_map->get('view_registry')));
        $this->assertTrue(is_callable($dep_map->get('url_builder')));
    }

    /**
     * @expectedException \OutOfBoundsException
     * @expectedExceptionMessage unknown key foo
     */
    public function testGetException()
    {
        $dep_map = new DependencyMap();
        $dep_map->get('foo');
    }
}
