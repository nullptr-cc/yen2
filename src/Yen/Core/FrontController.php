<?php

namespace Yen\Core;

use Yen\Http;
use Yen\Handler;

class FrontController
{
    protected $dc;

    public function __construct(Contract\IDependencyContainer $dc)
    {
        $this->dc = $dc;
    }

    public function processRequest(Http\Contract\IServerRequest $request)
    {
        $route = $this->dc->getRouter()->route($request->getUri()->getPath());
        $handler = $this->dc->getHandlerRegistry()->get($route->entry());
        $response = $handler->handle($request->getMethod(), $this->makeHandlerRequest($request, $route->arguments()));
        $view = $this->dc->getViewRegistry()->get($route->entry());

        return $view->present($request->getMethod(), $response);
    }

    protected function makeHandlerRequest(Http\Contract\IServerRequest $request, array $arguments)
    {
        return new Handler\Request($request, $arguments);
    }
}
