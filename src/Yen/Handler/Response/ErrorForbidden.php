<?php

namespace Yen\Handler\Response;

class ErrorForbidden extends Error
{
    public function __construct($message = null)
    {
        parent::__construct(403, $message);
    }
}
