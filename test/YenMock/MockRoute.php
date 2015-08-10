<?php

namespace YenMock;

trait MockRoute
{
    protected function mockRoute($entry, $args = [])
    {
        $route = $this->getMockBuilder('\Yen\Router\Route')
                      ->disableOriginalConstructor()
                      ->getMock();

        $route->method('entry')->willReturn($entry);
        $route->method('arguments')->willReturn($args);

        return $route;
    }
}
