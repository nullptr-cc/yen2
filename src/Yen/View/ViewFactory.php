<?php

namespace Yen\View;

use Yen\Core;

class ViewFactory implements Core\Contract\IFactory, Contract\IViewFactory
{
    protected $dc;
    protected $view_format;

    public function __construct(Core\Contract\IContainer $dc, $view_format = '\\%s')
    {
        $this->dc = $dc;
        $this->view_format = $view_format;
    }

    public function make($name)
    {
        return $this->makeView($name);
    }

    public function canMake($name)
    {
        return class_exists($this->resolveClassname($name));
    }

    public function makeView($view_name)
    {
        $classname = $this->resolveClassname($view_name);
        if (class_exists($classname)) {
            return $this->makeExistentView($classname);
        } else {
            return $this->makeDefaultView($classname);
        };
    }

    protected function makeExistentView($classname)
    {
        return new $classname($this->dc);
    }

    protected function makeDefaultView($classname)
    {
        return new DefaultView($this->dc);
    }

    protected function resolveClassname($view_name)
    {
        return sprintf($this->view_format, implode('\\', array_map('ucfirst', explode('/', $view_name))));
    }
}
