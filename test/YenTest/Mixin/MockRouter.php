<?php

namespace YenTest\Mixin;

trait MockRouter
{
    protected function mockRouter()
    {
        return $this->getMockBuilder('\Yen\Router\Router')
                    ->disableOriginalConstructor()
                    ->getMock();
    }
}
