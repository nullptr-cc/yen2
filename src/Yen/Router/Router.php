<?php

namespace Yen\Router;

use Yen\Router\Contract\IRouter;
use Yen\Router\Exception\RuleSyntaxError;
use Yen\Router\Exception\RouteNotFound;

class Router implements IRouter
{
    private $rules;

    public function __construct()
    {
        $this->rules = [];
    }

    public function addRule($name, Rule $rule)
    {
        if (array_key_exists($name, $this->rules)) {
            throw new \LogicException('Rule with name "' . $name . '" already added');
        };

        $this->rules[$name] = $rule;

        return $this;
    }

    /**
     * @return Yen\Router\Contract\IRoute
     */
    public function route($uri)
    {
        foreach ($this->rules as $rule) {
            $r = $rule->match($uri);
            if ($r->entry !== null) {
                return new Route($r->entry, $r->args);
            };
        };

        throw new RouteNotFound($uri);
    }

    public function resolve($name, $args)
    {
        if (isset($this->rules[$name])) {
            return $this->rules[$name]->apply($args);
        };

        throw new \LogicException('Unknown rule "' . $name . '"');
    }

    public static function createDefault()
    {
        $router = new self();
        $router->addRule('default', new Rule('/*', '$uri'));

        return $router;
    }

    public static function createFromRulesFile($file_path)
    {
        if (!is_readable($file_path)) {
            throw new \RuntimeException('Cannot open stream: ' . $file_path);
        };

        $router = new self();
        $lines = file($file_path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

        foreach ($lines as $index => $line) {
            $rule_info = self::processRuleLine($index, $line);
            $router->addRule($rule_info['name'], new Rule($rule_info['location'], $rule_info['result']));
        };

        return $router;
    }

    private static function processRuleLine($index, $line)
    {
        $lr = array_filter(array_map('trim', explode('=>', $line)));
        if (count($lr) != 2) {
            throw new RuleSyntaxError($index, $line);
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
