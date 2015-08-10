<?php

namespace YenMock;

trait MockDC
{
    protected function mockDC(array $map = [])
    {
        $dc = $this->getMockBuilder('\Yen\Core\DC')
                   ->disableOriginalConstructor()
                   ->getMock();

        $dc->method('__call')->will(
            $this->returnCallback(function($arg) use ($map) {
                return $map[$arg];
            })
        );

        return $dc;
    }
}
