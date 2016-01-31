<?php

namespace Yen\Util;

abstract class Registry implements Contract\IContainer
{
    protected $repo;

    public function __construct()
    {
        $this->repo = [];
    }

    public function get($name)
    {
        if (!$this->has($name)) {
            throw new \InvalidArgumentException('unknown name "' . $name . '"');
        };

        if (!isset($this->repo[$name])) {
            $this->repo[$name] = $this->create($name);
        };

        return $this->repo[$name];
    }

    abstract protected function create($name);
}
