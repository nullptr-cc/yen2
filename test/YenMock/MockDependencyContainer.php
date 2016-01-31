<?php

namespace YenMock;

trait MockDependencyContainer
{
    protected function mockDependencyContainer()
    {
        $dc = $this->getMockBuilder('\Yen\Core\DependencyContainer')
                   ->disableOriginalConstructor()
                   ->getMock();

        return $dc;
    }
}
