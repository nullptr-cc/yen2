<?php

namespace Yen\Core;

class DependencyContainer implements Contract\IDependencyContainer
{
    protected $instances = [];

    public function getRouter()
    {
        return $this->lazy('router', [$this, 'makeRouter']);
    }

    public function getHandlerRegistry()
    {
        return $this->lazy('handler_registry', [$this, 'makeHandlerRegistry']);
    }

    public function getViewRegistry()
    {
        return $this->lazy('view_registry', [$this, 'makeViewRegistry']);
    }

    protected function lazy($key, Callable $creator)
    {
        if (!array_key_exists($key, $this->instances)) {
            $this->instances[$key] = $creator();
        };

        return $this->instances[$key];
    }

    protected function makeRouter()
    {
        return \Yen\Router\Router::createDefault();
    }

    protected function makeHandlerRegistry()
    {
        return new \Yen\Handler\HandlerRegistry($this->makeHandlerFactory());
    }

    protected function makeViewRegistry()
    {
        return new \Yen\View\ViewRegistry($this->makeViewFactory());
    }

    protected function makeHandlerFactory()
    {
        return new \Yen\Handler\HandlerFactory();
    }

    protected function makeViewFactory()
    {
        return new \Yen\View\ViewFactory();
    }
}
