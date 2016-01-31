<?php

namespace Yen\View;

use Yen\Util\Contract\IFactory;
use Yen\View\Contract\IViewFactory;

class ViewFactory implements IFactory, IViewFactory
{
    protected $view_format;

    public function __construct($view_format = '\\%s')
    {
        $this->view_format = $view_format;
    }

    public function make($name)
    {
        return $this->makeView($name);
    }

    public function canMake($name)
    {
        return true;
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
        return new $classname();
    }

    protected function makeDefaultView($classname)
    {
        return new DefaultView();
    }

    protected function resolveClassname($view_name)
    {
        return sprintf($this->view_format, implode('\\', array_map('ucfirst', explode('/', $view_name))));
    }
}
