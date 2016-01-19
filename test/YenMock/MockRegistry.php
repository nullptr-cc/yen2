<?php

namespace YenMock;

trait MockRegistry
{
    protected function mockRegistry()
    {
        return $this->getMockBuilder('\Yen\Core\FactoryRegistry')
                    ->disableOriginalConstructor()
                    ->getMock();
    }
}
