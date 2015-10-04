<?php

namespace Yen\Core;

class DependencyMap
{
    public function get()
    {
        return [
            'router' => [$this, 'makeRouter'],
            'handler_registry' => [$this, 'makeHandlerRegistry'],
            'view_registry' => [$this, 'makeViewRegistry'],
            'url_builder' => [$this, 'makeUrlBuilder'],
        ];
    }

    public function makeRouter(Contract\IContainer $dc)
    {
        return \Yen\Router\Router::createDefault();
    }

    public function makeHandlerRegistry(Contract\IContainer $dc)
    {
        return new \Yen\Core\FactoryRegistry($this->makeHandlerFactory($dc));
    }

    public function makeViewRegistry(Contract\IContainer $dc)
    {
        return new \Yen\Core\FactoryRegistry($this->makeViewFactory($dc));
    }

    public function makeUrlBuilder(Contract\IContainer $dc)
    {
        return new \Yen\Core\UrlBuilder($dc->get('router'));
    }

    protected function makeHandlerFactory(Contract\IContainer $dc)
    {
        return new \Yen\Handler\HandlerFactory($dc);
    }

    protected function makeViewFactory(Contract\IContainer $dc)
    {
        return new \Yen\View\ViewFactory($dc);
    }
}
