<?php

namespace Yen\Router;

use Yen\Router\Contract\IRouter;
use Yen\Router\Exception\RouteSyntaxError;
use Yen\Router\Exception\RouteNotFound;

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
        if (!is_readable($file_path)) {
            throw new \RuntimeException('Cannot open stream: ' . $file_path);
        };

        $router = new self();
        $lines = file($file_path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

        foreach ($lines as $index => $line) {
            $rule_info = self::processRuleLine($index, $line);
            $router->add($rule_info['name'], new Route($rule_info['location'], $rule_info['result']));
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

    private static function processRuleLine($index, $line)
    {
        $lr = array_filter(array_map('trim', explode('=>', $line)));
        if (count($lr) != 2) {
            throw new RouteSyntaxError($index, $line);
        };

        list($location, $result) = $lr;

        if ($location[0] == '@') {
            $nl = explode(' ', $location, 2);
            $name = substr($nl[0], 1);
            $location = trim($nl[1]);
        } else {
            $name = sprintf('route%02d', $index);
        };

        return compact('name', 'location', 'result');
    }
}
