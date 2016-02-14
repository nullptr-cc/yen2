<?php

namespace YenMock;

trait MockViewRegistry
{
    protected function mockViewRegistry()
    {
        return $this->getMockBuilder(\Yen\View\ViewRegistry::class)
                    ->disableOriginalConstructor()
                    ->getMock();
    }
}
