<?php

namespace Yen\Core;

class MapRegistry extends Registry
{
    protected $map;

    public function __construct(array $map)
    {
        parent::__construct();
        $this->map = $map;
    }

    public function has($name)
    {
        return array_key_exists($name, $this->map);
    }

    protected function create($name)
    {
        return call_user_func($this->map[$name], $this);
    }
}
