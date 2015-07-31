<?php

namespace YenTest\Core;

use Yen\Core\UrlBuilder;

class UrlBuilderTest extends \PHPUnit_Framework_TestCase
{
    public function testBuildSimple()
    {
        $dc = $this->getMockBuilder('Yen\Core\DC')->getMock();

        $url_builder = new UrlBuilder($dc, \Yen\Http\Uri::createFromString('http://test.net'));

        $url = $url_builder->build('/search', ['q' => 'search query']);
        $this->assertInstanceOf('Yen\Http\Contract\IUri', $url);
        $this->assertEquals('http://test.net/search?q=search+query', $url->__toString());

        $url = $url_builder('/search', ['q' => 'search query']);
        $this->assertInstanceOf('Yen\Http\Contract\IUri', $url);
        $this->assertEquals('http://test.net/search?q=search+query', $url->__toString());
    }

    public function testBuildNamedRoute()
    {
        $router = $this->getMockBuilder('Yen\Router\Router')
                       ->setMethods(['resolve'])
                       ->getMock();

        $router->expects($this->exactly(2))
               ->method('resolve')
               ->with($this->equalTo('foo'), $this->equalTo(['bar' => 'baz']))
               ->willReturn((object)['uri' => '/test/baz', 'args' => []]);

        $dc = $this->getMockBuilder('Yen\Core\DC')
                   ->setMethods(['__call'])
                   ->getMock();

        $dc->expects($this->exactly(2))
           ->method('__call')
           ->with($this->equalTo('router'))
           ->willReturn($router);

        $url_builder = new UrlBuilder($dc, new \Yen\Http\Uri());

        $url = $url_builder->build('route:foo', ['bar' => 'baz']);
        $this->assertInstanceOf('Yen\Http\Contract\IUri', $url);
        $this->assertEquals('/test/baz', $url->__toString());

        $url = $url_builder('route:foo', ['bar' => 'baz']);
        $this->assertInstanceOf('Yen\Http\Contract\IUri', $url);
        $this->assertEquals('/test/baz', $url->__toString());
    }

    public function testBuildWithoutBase()
    {
        $dc = $this->getMockBuilder('Yen\Core\DC')->getMock();

        $url_builder = new UrlBuilder($dc);

        $url = $url_builder->build('http://test.net/search', ['q' => 'search query']);
        $this->assertInstanceOf('Yen\Http\Contract\IUri', $url);
        $this->assertEquals('http://test.net/search?q=search+query', $url->__toString());

        $url = $url_builder('http://test.net/search', ['q' => 'search query']);
        $this->assertInstanceOf('Yen\Http\Contract\IUri', $url);
        $this->assertEquals('http://test.net/search?q=search+query', $url->__toString());
    }
}
