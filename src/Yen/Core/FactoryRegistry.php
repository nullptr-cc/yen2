<?php

namespace Yen\Core;

class FactoryRegistry extends Registry
{
    protected $factory;

    public function __construct(Contract\IFactory $factory)
    {
        parent::__construct();
        $this->factory = $factory;
    }

    public function has($name)
    {
        return array_key_exists($name, $this->repo) || $this->factory->canMake($name);
    }

    protected function create($name)
    {
        return $this->factory->make($name);
    }
}
