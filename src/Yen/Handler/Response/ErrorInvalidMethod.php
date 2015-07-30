<?php

namespace Yen\Handler\Response;

class ErrorInvalidMethod extends Error
{
    public function __construct($message = null)
    {
        parent::__construct(405, $message);
    }
}
