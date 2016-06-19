<?php

namespace Yen\Router\Contract;

interface IRouter
{
    /**
     * @return Yen\Router\Contract\IRoute
     */
    public function route($uri);

    /**
     * @return stdClass{uri : string, args: map<string, string>}
     */
    public function resolve($name, $args);
}
