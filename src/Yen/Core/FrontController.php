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
        $route = $this->dc->get('router')->route($request->getUri()->getPath());
        $handler = $this->dc->get('handler_factory')->makeHandler($route->entry());
        $response = $handler->handle($request->getMethod(), new Handler\Request($request, $route->arguments()));
        $view = $this->dc->get('view_factory')->makeView($route->entry());

        return $view->handle($request->getMethod(), $response);
    }
}
