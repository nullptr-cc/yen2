<?php

namespace YenTest\Router;

use Yen\Router\Route;

class RouteTest extends \PHPUnit_Framework_TestCase
{
    public function test()
    {
        $route = new Route(null, null);
        $this->assertNull($route->entry());
        $this->assertNull($route->arguments());

        $route = new Route('/end/point', ['foo' => 'bar', 'baz' => 'bat']);
        $this->assertEquals('/end/point', $route->entry());
        $this->assertEquals(['foo' => 'bar', 'baz' => 'bat'], $route->arguments());
    }
}
