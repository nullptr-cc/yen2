<?php

namespace YenMock;

trait MockRegistry
{
    protected function mockRegistry()
    {
        return $this->getMockBuilder('\Yen\Util\FactoryRegistry')
                    ->disableOriginalConstructor()
                    ->getMock();
    }
}
