<?php

namespace Yen\Handler;

class MissedHandler
{
    protected $message;

    public function __construct($classname)
    {
        $this->message = $classname;
    }

    public function handle($method, Request $request)
    {
        return new Response\ErrorNotFound($this->message);
    }
}
