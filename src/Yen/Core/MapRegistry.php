<?php

namespace Yen\Core;

class MapRegistry extends Registry
{
    protected $map;

    public function __construct(Contract\IContainer $map)
    {
        parent::__construct();
        $this->map = $map;
    }

    public function has($name)
    {
        return $this->map->has($name);
    }

    protected function create($name)
    {
        return call_user_func($this->map->get($name), $this);
    }
}
