<?php

namespace YenMock;

class ArrayContainer extends \ArrayObject implements \Yen\Core\Contract\IContainer
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
