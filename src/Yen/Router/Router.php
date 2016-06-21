<?php

namespace Yen\Router;

use Yen\Router\Contract\IRouter;
use Yen\Router\Exception\RouteNotFound;
use Yen\Router\RoutesFileParser\Parser;

class Router implements IRouter
{
    private $routes;

    public function __construct()
    {
        $this->routes = [];
    }

    /**
     * @return Yen\Router\Contract\IRoutePoint
     */
    public function route($uri)
    {
        foreach ($this->routes as $route) {
            $result = $route->match($uri);
            if ($result->matched()) {
                return $result->point();
            };
        };

        throw new RouteNotFound($uri);
    }

    /**
     * @return Yen\Router\Contract\IRoutePoint
     */
    public function resolve($name, array $args)
    {
        if (!isset($this->routes[$name])) {
            throw new \LogicException('Unknown route: ' . $name);
        };

        return $this->routes[$name]->apply($args);
    }

    public static function createDefault()
    {
        $router = new self();
        $router->add('default', new Route('/*', '$uri'));

        return $router;
    }

    public static function createFromRoutesFile($file_path)
    {
        $parser = new Parser($file_path);
        $router = new self();

        foreach ($parser->parse() as $result) {
            $route = new Route($result->location(), $result->result());
            $router->add($result->name(), $route);
        };

        return $router;
    }

    private function add($name, Route $route)
    {
        if (array_key_exists($name, $this->routes)) {
            throw new \LogicException('Route with name "' . $name . '" already added');
        };

        $this->routes[$name] = $route;

        return $this;
    }
}
