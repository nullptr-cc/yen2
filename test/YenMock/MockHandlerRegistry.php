<?php

namespace YenMock;

trait MockHandlerRegistry
{
    protected function mockHandlerRegistry()
    {
        return $this->getMockBuilder(\Yen\Handler\HandlerRegistry::class)
                    ->disableOriginalConstructor()
                    ->getMock();
    }
}
