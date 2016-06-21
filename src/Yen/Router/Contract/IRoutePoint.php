<?php

namespace Yen\Router\Contract;

interface IRoutePoint
{
    /**
     * @return string
     */
    public function path();

    /**
     * @return map<string, string>
     */
    public function arguments();
}
