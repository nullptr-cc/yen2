<?php

namespace YenTest\Router;

use Yen\Router\Rule;

class RuleTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider dataMatch
     */
    public function testMatch($location, $result, $uri, $entry, $args)
    {
        $rule = new Rule($location, $result);

        $route = $rule->match($uri);
        $this->assertInstanceOf('stdClass', $route);
        $this->assertObjectHasAttribute('entry', $route);
        $this->assertObjectHasAttribute('args', $route);
        $this->assertEquals($entry, $route->entry);
        $this->assertEquals($args, $route->args);
    }

    public function dataMatch()
    {
        return [
            ['/', '$uri', '/', '', []],
            ['/', '$uri', '/test', 'test', []],
            ['/', '$uri', '/test/foo', 'test/foo', []],
            ['/test/:foo', 'test/info', '/test/bar', 'test/info', ['foo' => 'bar']],
            ['/test/:foo', 'test/info', '/test/baz', 'test/info', ['foo' => 'baz']],
            ['/test/:foo/(:bar = info)', 'test/$bar', '/test/baz', 'test/info', ['foo' => 'baz']],
            ['/test/:foo/(:bar = info)', 'test/$bar', '/test/baz/bat', 'test/bat', ['foo' => 'baz']]
        ];
    }

    public function testNotMatch()
    {
        $rule = new Rule('/test', 'test');

        $this->assertFalse($rule->match('/foo/bar'));
    }

    /**
     * @dataProvider dataApply
     */
    public function testApply($location, $result, $in_args, $uri, $out_args)
    {
        $rule = new Rule($location, $result);

        $res = $rule->apply($in_args);
        $this->assertInstanceOf('stdClass', $res);
        $this->assertObjectHasAttribute('uri', $res);
        $this->assertObjectHasAttribute('args', $res);
        $this->assertEquals($uri, $res->uri);
        $this->assertEquals($out_args, $res->args);
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
            ['/test/:foo/(:bar = info)', 'test/$bar', ['foo' => 'baz', 'bar' => 'bat', 'x' => 'y'], '/test/baz/bat', ['x' => 'y']],
            ['/test/(foo)', 'test/foo', [], '/test/foo', []],
            ['/test/(:foo)', 'test/foo', ['foo' => 'bar'], '/test/bar', []],
            ['/test/(:foo)', 'test/foo', [], '/test/', []],
        ];
    }
}
