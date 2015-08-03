<?php

namespace YenTest\Handler;

class CustomHandler extends \Yen\Handler\Handler
{
    protected function onGet($request)
    {
        return new \Yen\Handler\Response\Ok();
    }
}
