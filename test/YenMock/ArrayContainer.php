<?php

namespace YenMock;

class ArrayContainer extends \ArrayObject implements \Yen\Util\Contract\IContainer
{
    public function has($key)
    {
        return $this->offsetExists($key);
    }

    public function get($key)
    {
        return $this->offsetGet($key);
    }
}
