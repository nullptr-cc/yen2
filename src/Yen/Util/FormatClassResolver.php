<?php

namespace Yen\Util;

class FormatClassResolver implements Contract\IClassResolver
{
    protected $format;
    protected $fallback;

    public function __construct($format, $fallback = '')
    {
        $this->format = $format;
        $this->fallback = $fallback;
    }

    public function resolve($string)
    {
        $name = implode('\\', array_map('ucfirst', explode('/', $string)));
        $classname = sprintf($this->format, $name);

        if (class_exists($classname)) {
            return $classname;
        } else {
            return $this->fallback;
        };
    }
}
