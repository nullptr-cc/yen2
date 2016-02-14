<?php

namespace Yen\Handler;

use Yen\Http;

class MissedHandler implements Contract\IHandler
{
    protected $message;

    public function __construct($classname)
    {
        $this->message = 'Handler class ' . $classname . ' not found';
    }

    public function handle(Http\Contract\IServerRequest $request)
    {
        return new Response\ErrorNotFound($this->message);
    }
}
