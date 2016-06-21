<?php

namespace Yen\Router\Contract;

interface IRouter
{
    /**
     * @return Yen\Router\Contract\IRoutePoint
     */
    public function route($uri);

    /**
     * @return Yen\Router\Contract\IRoutePoint
     */
    public function resolve($name, array $args);
}
