<?php

namespace Yen\Core;

use Yen\Http;
use Yen\Router;
use Yen\Handler;
use Yen\View;

class FrontController
{
    protected $router;
    protected $handler_registry;
    protected $view_registry;

    public function __construct(
        Router\Contract\IRouter $router,
        Handler\Contract\IHandlerRegistry $handler_registry,
        View\Contract\IViewRegistry $view_registry
    ) {
        $this->router = $router;
        $this->handler_registry = $handler_registry;
        $this->view_registry = $view_registry;
    }

    public static function createFromDC(Contract\IDependencyContainer $dc)
    {
        return new self(
            $dc->getRouter(),
            $dc->getHandlerRegistry(),
            $dc->getViewRegistry()
        );
    }

    public function processRequest(Http\Contract\IServerRequest $request)
    {
        $route = $this->router->route($request->getUri()->getPath());
        $handler = $this->handler_registry->getHandler($route->entry());
        $response = $handler->handle($request->withJoinedQueryParams($route->arguments()));
        $view = $this->view_registry->getView($route->entry());

        return $view->present($request->getMethod(), $response);
    }
}
