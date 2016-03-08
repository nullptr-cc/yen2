<?php

namespace YenMock\Handler;

class CustomHandler extends \Yen\Handler\Handler
{
    protected function onGet($request)
    {
        return new \Yen\Http\Response(200, [], '');
    }

    protected function getPresenter()
    {
    }

    protected function getErrorPresenter()
    {
    }
}
