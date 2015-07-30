<?php

namespace Yen\Router\Contract;

interface IRoute
{
    public function entry();
    public function arguments();
}
