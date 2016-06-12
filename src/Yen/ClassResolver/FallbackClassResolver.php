<?php

namespace Yen\ClassResolver;

use Yen\ClassResolver\Contract\IClassResolver;

class FallbackClassResolver implements IClassResolver
{
    private $resolver;
    private $fallback_classname;

    public function __construct(IClassResolver $resolver, $fallback_classname)
    {
        if (!class_exists($fallback_classname))  {
            throw new \InvalidArgumentException('Class not found: ' . $fallback_classname);
        };

        $this->resolver = $resolver;
        $this->fallback_classname = $fallback_classname;
    }

    public function resolve($string)
    {
        try {
            return $this->resolver->resolve($string);
        } catch (ClassNotResolved $ex) {
            return $this->fallback_classname;
        }
    }
}
