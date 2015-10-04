<?php

namespace Yen\Core;

use Yen\Http;
use Yen\Handler;

class FrontController
{
    protected $dc;

    public function __construct(Contract\IContainer $dc)
    {
        $this->dc = $dc;
    }

    public function processRequest(Http\Contract\IServerRequest $request)
    {
        $route = $this->dc->get('router')->route($request->getUri()->getPath());
        $handler = $this->dc->get('handler_registry')->get($route->entry());
        $response = $handler->handle($request->getMethod(), $this->makeHandlerRequest($request, $route->arguments()));
        $view = $this->dc->get('view_registry')->get($route->entry());

        return $view->present($request->getMethod(), $response);
    }

    protected function makeHandlerRequest(Http\Contract\IServerRequest $request, array $arguments)
    {
        return new Handler\Request($request, $arguments);
    }
}
