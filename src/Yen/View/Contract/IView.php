<?php

namespace Yen\View\Contract;

interface IView
{
    public function present($method, \Yen\Handler\Contract\IResponse $response);
}
