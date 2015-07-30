<?php

namespace Yen\View;

use Yen\Core;

class ViewFactory
{
    protected $dc;
    protected $view_format;

    public function __construct(Core\Contract\IDependencyContainer $dc, $view_format = '\\%s')
    {
        $this->dc = $dc;
        $this->view_format = $view_format;
    }

    public function make($view_name)
    {
        $classname = sprintf($this->view_format, static::e2c($view_name));
        if (class_exists($classname)) {
            return new $classname($this->dc);
        } else {
            return $this->makeNullView();
        };
    }

    public function makeNullView()
    {
        return new NullView($this->dc);
    }

    protected static function e2c($name)
    {
        return implode('\\', array_map('ucfirst', explode('/', $name)));
    }
}
