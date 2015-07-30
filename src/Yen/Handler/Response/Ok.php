<?php

namespace Yen\Handler\Response;

class Ok extends \Yen\Handler\Response
{
    public function __construct($data = null)
    {
        parent::__construct(200, $data);
    }

    public function isOk()
    {
        return true;
    }
}
