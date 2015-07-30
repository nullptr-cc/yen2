<?php

namespace Yen\Handler;

use Yen\Core;

class HandlerFactory
{
    protected $dc;
    protected $format;

    public function __construct(Core\Contract\IDependencyContainer $dc, $format = '\\%s')
    {
        $this->dc = $dc;
        $this->format = $format;
    }

    public function make($name)
    {
        $classname = sprintf($this->format, static::e2c($name));
        if (class_exists($classname)) {
            return new $classname($this->dc);
        } else {
            return $this->makeNullHandler($classname);
        };
    }

    protected function makeNullHandler($classname)
    {
        return new NullHandler('Missed handler ' . $classname);
    }

    protected static function e2c($name)
    {
        return implode('\\', array_map('ucfirst', explode('/', $name)));
    }
}
