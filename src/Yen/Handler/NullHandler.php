<?php

namespace Yen\Handler;

class NullHandler
{
    protected $message;

    public function __construct($message)
    {
        $this->message = $message;
    }

    public function handle($method, Request $request)
    {
        return new Response\ErrorNotFound($this->message);
    }
}
