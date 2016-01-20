<?php

namespace Yen\Core;

class DependencyMap implements Contract\IContainer
{
    protected $map;

    public function __construct()
    {
        $this->map = [
            'router' => [$this, 'makeRouter'],
            'handler_registry' => [$this, 'makeHandlerRegistry'],
            'view_registry' => [$this, 'makeViewRegistry'],
            'url_builder' => [$this, 'makeUrlBuilder'],
        ];
    }

    public function has($key)
    {
        return array_key_exists($key, $this->map);
    }

    public function get($key)
    {
        if (!$this->has($key)) {
            throw new \OutOfBoundsException('unknown key ' . $key);
        };

        return $this->map[$key];
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
