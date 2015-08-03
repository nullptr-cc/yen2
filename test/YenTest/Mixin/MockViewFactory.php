<?php

namespace YenTest\Mixin;

trait MockViewFactory
{
    protected function mockViewFactory()
    {
        return $this->getMockBuilder('\Yen\View\ViewFactory')
                    ->disableOriginalConstructor()
                    ->getMock();
    }
}
