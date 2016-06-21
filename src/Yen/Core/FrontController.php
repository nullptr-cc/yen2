<?php

namespace Yen\Core;

use Yen\Http\Contract\IServerRequest;
use Yen\Http\Contract\IResponse;
use Yen\Http\Response;
use Yen\Router\Contract\IRouter;
use Yen\Handler\Contract\IHandlerRegistry;
use Yen\Handler\HandlerNotFoundException;

class FrontController
{
    protected $router;
    protected $handlers;

    public function __construct(
        IRouter $router,
        IHandlerRegistry $handlers
    ) {
        $this->router = $router;
        $this->handlers = $handlers;
    }

    public function processRequest(IServerRequest $request)
    {
        $route_point = $this->router->route($request->getUri()->getPath());

        if (!$this->handlers->hasHandler($route_point->path())) {
            return $this->response(IResponse::STATUS_NOT_FOUND);
        };

        $handler = $this->handlers->getHandler($route_point->path());

        if (!in_array($request->getMethod(), $handler->getAllowedMethods())) {
            return $this->response(IResponse::STATUS_METHOD_NOT_ALLOWED);
        };

        $response = $handler->handle($request->withJoinedQueryParams($route_point->arguments()));

        return $response;
    }

    protected function response($code)
    {
        return new Response($code, [], '');
    }
}
