<?php

namespace Yen\Core;

class DC implements Contract\IDependencyContainer
{
    protected $map;
    protected $repo;

    public function __construct(array $map = [])
    {
        $this->map = $map;
        $this->repo = [];
    }

    public function has($name)
    {
        return array_key_exists($name, $this->map);
    }

    public function get($name)
    {
        if (!$this->has($name)) {
            throw new \InvalidArgumentException('DC error: unknown name "' . $name . '"');
        };

        if (!isset($this->repo[$name])) {
            $this->repo[$name] = call_user_func($this->map[$name], $this);
        };

        return $this->repo[$name];
    }
}
