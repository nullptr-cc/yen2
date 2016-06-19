<?php

namespace Yen\Router\Exception;

class RouteNotFound extends \Exception
{
    public function __construct($uri)
    {
        $this->message = sprintf('Route for URI "%s" not found', $uri);
    }
}
