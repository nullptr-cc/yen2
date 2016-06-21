<?php

namespace YenTest\Router;

use Yen\Router\Route;
use Yen\Router\RoutePoint;
use Yen\Router\MatchResult;

class RouteTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider dataMatch
     */
    public function testMatch($location, $result, $uri, $path, $args)
    {
        $route = new Route($location, $result);

        $result = $route->match($uri);
        $this->assertInstanceOf(MatchResult::class, $result);
        $this->assertTrue($result->matched());
        $this->assertSame($path, $result->point()->path());
        $this->assertSame($args, $result->point()->arguments());
    }

    public function dataMatch()
    {
        return [
            ['/', '$uri', '/', '', []],
            ['/*', '$uri', '/', '', []],
            ['/*', '$uri', '/test', 'test', []],
            ['/*', '$uri', '/test/foo/bar', 'test/foo/bar', []],
            ['/+', '$uri', '/test', 'test', []],
            ['/+', '$uri', '/test/foo/bar', 'test/foo/bar', []],
            ['/test', 'test/foo/bar', '/test', 'test/foo/bar', []],
            ['/test/:foo', 'test/info', '/test/bar', 'test/info', ['foo' => 'bar']],
            ['/test/:foo', 'test/info', '/test/baz', 'test/info', ['foo' => 'baz']],
            ['/test/:foo/(:bar = info)', 'test/$bar', '/test/baz', 'test/info', ['foo' => 'baz']],
            ['/test/:foo/(:bar = info)', 'test/$bar', '/test/baz/bat', 'test/bat', ['foo' => 'baz']],
            ['/admin/*', 'cpanel/admin/$suffix', '/admin', 'cpanel/admin', []],
            ['/admin/*', 'cpanel/admin/$suffix', '/admin/users', 'cpanel/admin/users', []],
            ['/admin/+', 'cpanel/admin/$suffix', '/admin/users', 'cpanel/admin/users', []],
        ];
    }

    /**
     * @dataProvider dataNotMatch
     */
    public function testNotMatch($location, $result, $uri)
    {
        $route = new Route($location, $result);

        $result = $route->match($uri);
        $this->assertInstanceOf(MatchResult::class, $result);
        $this->assertFalse($result->matched());
    }

    public function dataNotMatch()
    {
        return [
            ['/', '$uri', '/test'],
            ['/+', '$uri', '/'],
            ['/test', 'test/foo/bar', '/test/foo'],
            ['/admin/+', 'cpanel/admin/$suffix', '/admin'],
        ];
    }

    /**
     * @dataProvider dataApply
     */
    public function testApply($location, $result, $in_args, $uri, $out_args)
    {
        $route = new Route($location, $result);

        $point = $route->apply($in_args);
        $this->assertInstanceOf(RoutePoint::class, $point);
        $this->assertEquals($uri, $point->path());
        $this->assertEquals($out_args, $point->arguments());
    }

    public function dataApply()
    {
        return [
            ['/', '$uri', [], '/', []],
            ['/', '$uri', ['foo' => 'bar'], '/', ['foo' => 'bar']],
            ['/test/:foo', 'test/info', ['foo' => 'bar'], '/test/bar', []],
            ['/test/:foo', 'test/info', ['foo' => 'bar', 'baz' => 'bat'], '/test/bar', ['baz' => 'bat']],
            ['/test/:foo/(:bar = info)', 'test/$bar', ['foo' => 'baz'], '/test/baz/info', []],
            ['/test/:foo/(:bar = info)', 'test/$bar', ['foo' => 'baz', 'bar' => 'bat'], '/test/baz/bat', []],
            [
                '/test/:foo/(:bar = info)', 'test/$bar',
                ['foo' => 'baz', 'bar' => 'bat', 'x' => 'y'],
                '/test/baz/bat', ['x' => 'y']
            ],
            ['/test/(foo)', 'test/foo', [], '/test/foo', []],
            ['/test/(:foo)', 'test/foo', ['foo' => 'bar'], '/test/bar', []],
            ['/test/(:foo)', 'test/foo', [], '/test/', []],
        ];
    }
}
