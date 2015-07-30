<?php

namespace Yen\Handler\Response;

class ErrorNotFound extends Error
{
    public function __construct($message = null)
    {
        parent::__construct(404, $message);
    }
}
