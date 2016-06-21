<?php

namespace YenTest\Router;

use Yen\Router\RoutePoint;

class RoutePointTest extends \PHPUnit_Framework_TestCase
{
    public function test()
    {
        $point = new RoutePoint(null, []);
        $this->assertNull($point->path());
        $this->assertEmpty($point->arguments());

        $point = new RoutePoint('/end/point', ['foo' => 'bar', 'baz' => 'bat']);
        $this->assertEquals('/end/point', $point->path());
        $this->assertEquals(['foo' => 'bar', 'baz' => 'bat'], $point->arguments());
    }
}
