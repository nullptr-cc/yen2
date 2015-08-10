<?php

namespace YenTest\Core;

use Yen\Core\UrlBuilder;

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

        $router->method('resolve')
               ->with($this->equalTo('foo'), $this->equalTo(['bar' => 'baz']))
               ->willReturn((object)['uri' => '/test/baz', 'args' => []]);

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

    /**
     * @expectedException InvalidArgumentException
     * @expectedExceptionMessage Unknown route "unknown"
     */
    public function testUnknownRoute()
    {
        $router = $this->mockRouter();
        $router->method('resolve')
               ->willReturn(null);

        $url_builder = new UrlBuilder($router);
        $uri = \Yen\Http\Uri::createFromString('route:unknown');

        $url_builder->build($uri);
    }
}
