<?php

namespace Yen\Router\Contract;

interface IRouter
{
    public function route($uri);
    public function resolve($name, $args);
}
