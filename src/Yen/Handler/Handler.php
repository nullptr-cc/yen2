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
            return $this->getErrorPresenter()->errorInvalidMethod();
        };

        $this->beforeHandle($request);
        $response = $this->{$on_name}($request);
        $this->afterHandle($request, $response);

        return $response;
    }

    abstract protected function getErrorPresenter();

    abstract protected function getPresenter();

    protected function beforeHandle(Http\Contract\IServerRequest $request)
    {
    }

    protected function afterHandle(Http\Contract\IServerRequest $request, Http\Contract\IResponse $response)
    {
    }

    protected function ok(...$args)
    {
        return $this->getPresenter()->present(...$args);
    }

    protected function error(...$args)
    {
        return $this->getErrorPresenter()->errorInternal(...$args);
    }

    protected function notFound(...$args)
    {
        return $this->getErrorPresenter()->errorNotFound(...$args);
    }

    protected function badParams(...$args)
    {
        return $this->getErrorPresenter()->errorInvalidParams(...$args);
    }

    protected function forbidden(...$args)
    {
        return $this->getErrorPresenter()->errorForbidden(...$args);
    }

    protected function redirect(Http\Contract\IUri $url, $persistent = false)
    {
        return new Http\Response(
            $persistent ? 301 : 302,
            ['Location' => $url],
            ''
        );
    }
}
