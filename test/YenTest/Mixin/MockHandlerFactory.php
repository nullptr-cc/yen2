<?php

namespace YenTest\Mixin;

trait MockHandlerFactory
{
    protected function mockHandlerFactory()
    {
        return $this->getMockBuilder('\Yen\Handler\HandlerFactory')
                    ->disableOriginalConstructor()
                    ->getMock();
    }
}
