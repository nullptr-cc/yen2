<?php

namespace Yen\Handler;

use Yen\Core;

abstract class Handler
{
    protected $dc;

    public function __construct(Core\Contract\IDependencyContainer $dc)
    {
        $this->dc = $dc;
    }

    public function handle($method, Contract\IRequest $request)
    {
        $name = ucfirst(strtolower($method));
        $on_name = 'on' . $name;

        if (!method_exists($this, $on_name)) {
            return new Response\ErrorInvalidMethod();
        };

        return $this->{$on_name}($request);
    }
}
