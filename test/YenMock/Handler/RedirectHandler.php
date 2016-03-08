<?php

namespace YenMock\Handler;

use Yen\Http\Contract\IUri;

class RedirectHandler extends \Yen\Handler\Handler
{
    public function redirect(IUri $uri, $persistent = false)
    {
        return parent::redirect($uri, $persistent);
    }

    protected function getPresenter()
    {
    }

    protected function getErrorPresenter()
    {
    }
}
