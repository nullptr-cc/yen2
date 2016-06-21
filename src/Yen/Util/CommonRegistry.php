<?php

namespace Yen\Util;

use Yen\ClassResolver\Contract\IClassResolver;
use Yen\ClassResolver\Exception\ClassNotResolved;

class CommonRegistry
{
    use LazyContainer;

    protected $resolver;

    public function __construct(IClassResolver $resolver)
    {
        $this->resolver = $resolver;
    }

    protected function get($name)
    {
        return $this->lazy($name, [$this, 'make']);
    }

    protected function has($name)
    {
        try {
            $this->resolver->resolve($name);
            return true;
        } catch (ClassNotResolved $ex) {
            return false;
        };
    }

    protected function make($name)
    {
        try {
            $classname = $this->resolver->resolve($name);
            return $this->createExistent($classname);
        } catch (ClassNotResolved $ex) {
            throw $this->createInvalidNameException($name);
        };
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
