<?php

namespace Yen\Core;

class Bootstrap
{
    public function initRouter(Contract\IDependencyContainer $dc)
    {
        return \Yen\Router\Router::createDefault();
    }

    public function initHandlerFactory(Contract\IDependencyContainer $dc)
    {
        return new \Yen\Handler\HandlerFactory($dc);
    }

    public function initViewFactory(Contract\IDependencyContainer $dc)
    {
        return new \Yen\View\ViewFactory($dc);
    }

    public function initRendererFactory(Contract\IDependencyContainer $dc)
    {
        return new \Yen\Renderer\RendererFactory();
    }

    public function initUrlBuilder(Contract\IDependencyContainer $dc)
    {
        return new \Yen\Core\UrlBuilder($dc);
    }

    public function bootstrap()
    {
        return [
            'router' => [$this, 'initRouter'],
            'handler_factory' => [$this, 'initHandlerFactory'],
            'view_factory' => [$this, 'initViewFactory'],
            'renderer_factory' => [$this, 'initRendererFactory'],
            'url_builder' => [$this, 'initUrlBuilder'],
        ];
    }
}
