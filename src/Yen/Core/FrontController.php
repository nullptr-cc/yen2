<?php

namespace Yen\Core;

use Yen\Http\Contract\IServerRequest;
use Yen\Router\Contract\IRouter;
use Yen\Handler\Contract\IHandlerRegistry;

class FrontController
{
    protected $router;
    protected $handler_registry;

    public function __construct(
        IRouter $router,
        IHandlerRegistry $handler_registry
    ) {
        $this->router = $router;
        $this->handler_registry = $handler_registry;
    }

    public function processRequest(IServerRequest $request)
    {
        $route = $this->router->route($request->getUri()->getPath());
        $handler = $this->handler_registry->getHandler($route->entry());
        $response = $handler->handle($request->withJoinedQueryParams($route->arguments()));

        return $response;
    }
}
