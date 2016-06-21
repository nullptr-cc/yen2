<?php

namespace YenTest\Util;

use Yen\Util\UrlBuilder;
use Yen\Router\RoutePoint;
use Yen\Router\Router;

class UrlBuilderTest extends \PHPUnit_Framework_TestCase
{
    use \YenMock\MockRouter;

    public function testBuildSimple()
    {
        $router = $this->mockRouter();

        $url_builder = new UrlBuilder($router, \Yen\Http\Uri::createFromString('http://test.net'));
        $uri = \Yen\Http\Uri::createFromString('/search');
        $args = ['q' => 'search query'];

        $url = $url_builder->build($uri, $args);
        $this->assertInstanceOf('Yen\Http\Contract\IUri', $url);
        $this->assertEquals('http://test.net/search?q=search+query', $url->__toString());

        $url = $url_builder($uri, $args);
        $this->assertInstanceOf('Yen\Http\Contract\IUri', $url);
        $this->assertEquals('http://test.net/search?q=search+query', $url->__toString());
    }

    public function testBuildNamedRoute()
    {
        $router = $this->mockRouter();
        $route_point = new RoutePoint('/test/baz', []);
        $router->method('resolve')
               ->with($this->equalTo('foo'), $this->equalTo(['bar' => 'baz']))
               ->willReturn($route_point);

        $url_builder = new UrlBuilder($router, new \Yen\Http\Uri());
        $uri = \Yen\Http\Uri::createFromString('route:foo');
        $args = ['bar' => 'baz'];

        $url = $url_builder->build($uri, $args);
        $this->assertInstanceOf('Yen\Http\Contract\IUri', $url);
        $this->assertEquals('/test/baz', $url->__toString());

        $url = $url_builder($uri, $args);
        $this->assertInstanceOf('Yen\Http\Contract\IUri', $url);
        $this->assertEquals('/test/baz', $url->__toString());
    }

    public function testBuildWithoutBase()
    {
        $router = $this->mockRouter();

        $url_builder = new UrlBuilder($router);
        $uri = \Yen\Http\Uri::createFromString('http://test.net/search');
        $args = ['q' => 'search query'];

        $url = $url_builder->build($uri, $args);
        $this->assertInstanceOf('Yen\Http\Contract\IUri', $url);
        $this->assertEquals('http://test.net/search?q=search+query', $url->__toString());

        $url = $url_builder($uri, $args);
        $this->assertInstanceOf('Yen\Http\Contract\IUri', $url);
        $this->assertEquals('http://test.net/search?q=search+query', $url->__toString());
    }

    public function testUnknownRoute()
    {
        $this->expectException(\LogicException::class);
        $this->expectExceptionMessage('Unknown route: unknown');

        $router = Router::createDefault();
        $url_builder = new UrlBuilder($router);
        $uri = \Yen\Http\Uri::createFromString('route:unknown');

        $url_builder->build($uri);
    }
}
