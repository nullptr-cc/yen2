<?php

namespace Yen\Handler;

class MissedHandler implements Contract\IHandler
{
    protected $message;

    public function __construct($classname)
    {
        $this->message = 'Handler class ' . $classname . ' not found';
    }

    public function handle($method, Contract\IRequest $request)
    {
        return new Response\ErrorNotFound($this->message);
    }
}
