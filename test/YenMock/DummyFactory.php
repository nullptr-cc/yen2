<?php

namespace YenMock;

class DummyFactory implements \Yen\Core\Contract\IFactory
{
    public function make($name)
    {
        return $name;
    }

    public function canMake($name)
    {
        return strlen($name) > 2;
    }
}
