<?php

namespace Yen\Core;

use Yen\Router\Contract\IRouter;
use Yen\Router\Exception\RouteNotFound;
use Yen\Handler\Contract\IHandlerRegistry;
use Yen\Handler\Exception\HandlerNotFound;
use Yen\Http\Contract\IServerRequest;

class FrontController
{
    private $router;
    private $handlers;

    public function __construct(
        IRouter $router,
        IHandlerRegistry $handlers
    ) {
        $this->router = $router;
        $this->handlers = $handlers;
    }

    public function processRequest(IServerRequest $request)
    {
        try {
            return $this->routeAndHandleRequest($request);
        } catch (RouteNotFound $ex) {
            return $this->handleNotFound($request);
        } catch (HandlerNotFound $ex) {
            return $this->handleNotFound($request);
        };
    }

    private function routeAndHandleRequest(IServerRequest $request)
    {
        $route_point = $this->router->route($request->getUri()->getPath());
        $handler = $this->handlers->getHandler($route_point->path());
        $response = $handler->handle($request->withJoinedQueryParams($route_point->arguments()));

        return $response;
    }

    private function handleNotFound(IServerRequest $request)
    {
        $handler = $this->handlers->getNotFoundHandler();
        $response = $handler->handle($request);

        return $response;
    }
}
