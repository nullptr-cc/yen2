<?php

namespace Yen\Handler;

use Yen\Core;
use Yen\Http;

abstract class Handler implements Contract\IHandler
{
    public function handle(Http\Contract\IServerRequest $request)
    {
        $name = ucfirst(strtolower($request->getMethod()));
        $on_name = 'on' . $name;

        if (!method_exists($this, $on_name)) {
            return new Response\ErrorInvalidMethod();
        };

        return $this->{$on_name}($request);
    }

    protected function ok($data)
    {
        return new Response\Ok($data);
    }

    protected function invalidParams($data)
    {
        return new Response\ErrorInvalidParams($data);
    }

    protected function forbidden($data)
    {
        return new Response\ErrorForbidden($data);
    }

    protected function notFound($data)
    {
        return new Response\ErrorNotFound($data);
    }

    protected function error($data)
    {
        return new Response\ErrorInternal($data);
    }
}
