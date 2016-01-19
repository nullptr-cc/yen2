<?php

namespace YenMock;

trait MockDC
{
    protected function mockDC(array $map = [])
    {
        $dc = $this->getMockBuilder('\Yen\Core\MapRegistry')
                   ->disableOriginalConstructor()
                   ->getMock();

        $dc->method('has')->will(
            $this->returnCallback(function ($arg) use ($map) {
                return array_key_exists($arg, $map);
            })
        );

        $dc->method('get')->will(
            $this->returnCallback(function ($arg) use ($map) {
                return $map[$arg];
            })
        );

        return $dc;
    }
}
