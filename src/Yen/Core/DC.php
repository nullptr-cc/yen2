<?php

namespace Yen\Core;

class DC implements Contract\IDependencyContainer
{
    protected $repo;
    protected $objects;

    public function __construct(array $repo = [])
    {
        $this->repo = $repo;
        $this->objects = [];
    }

    public function __call($method, $args)
    {
        if (!isset($this->repo[$method])) {
            throw new \BadMethodCallException(static::class . '::' . $method);
        };

        if (!isset($this->objects[$method])) {
            $this->objects[$method] = call_user_func($this->repo[$method], $this);
        };

        if (!empty($args)) {
            return call_user_func_array($this->objects[$method], $args);
        };

        return $this->objects[$method];
    }
}
