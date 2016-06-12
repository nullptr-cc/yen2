<?php

namespace Yen\ClassResolver;

use Yen\ClassResolver\Contract\IClassResolver;

class FormatClassResolver implements IClassResolver
{
    protected $format;

    public function __construct($format)
    {
        $this->format = $format;
    }

    public function resolve($string)
    {
        $name = implode('\\', array_map('ucfirst', explode('/', $string)));
        $classname = sprintf($this->format, $name);

        if (class_exists($classname)) {
            return $classname;
        } else {
            throw new ClassNotResolved($string);
        };
    }
}
