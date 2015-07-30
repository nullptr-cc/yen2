<?php

namespace Yen\Handler\Response;

class ErrorInvalidParams extends Error
{
    public function __construct($data = null)
    {
        parent::__construct(400, $data);
    }
}
