<?php

namespace Yen\Handler\Response;

class ErrorInternal extends Error
{
    public function __construct($message = null)
    {
        parent::__construct(500, $message);
    }
}
