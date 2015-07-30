<?php

namespace Yen\Handler\Response;

abstract class Error extends \Yen\Handler\Response
{
    public function isError()
    {
        return true;
    }
}
