<?php

namespace Yen\Handler;

use Yen\ClassResolver\Contract\IClassResolver;
use Yen\ClassResolver\Exception\ClassNotResolved;
use Yen\Handler\Contract\IHandlerFactory;
use Yen\Handler\Exception\HandlerNotMaked;

class HandlerFactory implements IHandlerFactory
{
    private $resolver;

    public function __construct(IClassResolver $resolver)
    {
        $this->resolver = $resolver;
    }

    public function makeHandler($name)
    {
        try {
            $classname = $this->resolver->resolve($name);
            return $this->make($classname);
        } catch (ClassNotResolved $ex) {
            throw new HandlerNotMaked($name, 0, $ex);
        }
    }

    public function makeResolved($classname)
    {
        return $this->make($classname);
    }

    protected function make($classname)
    {
        return new $classname();
    }
}
