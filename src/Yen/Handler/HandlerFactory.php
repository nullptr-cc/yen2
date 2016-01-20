<?php

namespace Yen\Handler;

use Yen\Core;

class HandlerFactory implements Core\Contract\IFactory, Contract\IHandlerFactory
{
    protected $dc;
    protected $format;

    public function __construct(Core\Contract\IContainer $dc, $format = '\\%s')
    {
        $this->dc = $dc;
        $this->format = $format;
    }

    public function make($name)
    {
        return $this->makeHandler($name);
    }

    public function canMake($name)
    {
        return true;
    }

    public function makeHandler($handler_name)
    {
        $classname = $this->resolveClassname($handler_name);
        if (class_exists($classname)) {
            return $this->makeExistentHandler($classname);
        } else {
            return $this->makeMissedHandler($classname);
        };
    }

    protected function makeExistentHandler($classname)
    {
        return new $classname($this->dc);
    }

    protected function makeMissedHandler($classname)
    {
        return new MissedHandler($classname);
    }

    protected function resolveClassname($handler_name)
    {
        return sprintf($this->format, implode('\\', array_map('ucfirst', explode('/', $handler_name))));
    }
}
