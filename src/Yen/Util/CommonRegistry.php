<?php

namespace Yen\Util;

class CommonRegistry
{
    use LazyContainer;

    protected $resolver;

    public function __construct(Contract\IClassResolver $resolver)
    {
        $this->resolver = $resolver;
    }

    protected function get($name)
    {
        return $this->lazy($name, [$this, 'make']);
    }

    protected function has($name)
    {
        return $this->resolver->resolve($name) != '';
    }

    protected function make($name)
    {
        $classname = $this->resolver->resolve($name);
        if (!$classname) {
            throw $this->createInvalidNameException($name);
        };

        return $this->createExistent($classname);
    }

    protected function createExistent($classname)
    {
        return new $classname();
    }

    protected function createInvalidNameException($name)
    {
        return new \OutOfBoundsException('Unknown ' . $name);
    }
}
