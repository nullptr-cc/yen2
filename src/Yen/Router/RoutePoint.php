<?php

namespace Yen\Router;

use Yen\Router\Contract\IRoutePoint;

class RoutePoint implements IRoutePoint
{
    private $path;
    private $arguments;

    public function __construct($path, array $arguments)
    {
        $this->path = $path;
        $this->arguments = $arguments;
    }

    public function path()
    {
        return $this->path;
    }

    public function arguments()
    {
        return $this->arguments;
    }
}
