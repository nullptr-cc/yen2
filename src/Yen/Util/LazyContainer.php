<?php

namespace Yen\Util;

trait LazyContainer
{
    protected $items = [];

    protected function lazy($key, Callable $creator)
    {
        if (!array_key_exists($key, $this->items)) {
            $this->items[$key] = $creator($key);
        };

        return $this->items[$key];
    }
}
