<?php

namespace Yen\Router;

class Router implements Contract\IRouter
{
    protected $rules;

    public function __construct($rules = [])
    {
        $this->rules = $rules;
    }

    public function route($uri)
    {
        foreach ($this->rules as $rule) {
            if ($r = $rule->match($uri)) {
                return $r;
            };
        };

        return new Route(null, null);
    }

    public function resolve($name, $args)
    {
        if (isset($this->rules[$name])) {
            return $this->rules[$name]->apply($args);
        };

        return null;
    }

    public static function createDefault()
    {
        return new self([new Rule('/', '$uri')]);
    }

    public static function createFromRulesFile($file_path)
    {
        if (!is_readable($file_path)) {
            throw new \InvalidArgumentException('Cannot open stream: ' . $file_path);
        };

        $rules = [];
        $lines = file($file_path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        foreach ($lines as $index => $line) {
            if ($rule_info = self::processRuleLine($line)) {
                $name = $rule_info['name'] ?: sprintf('route%02d', $index);
                $rules[$name] = new Rule($rule_info['location'], $rule_info['result']);
            };
        };

        return new self($rules);
    }

    private static function processRuleLine($line)
    {
        $lr = array_filter(array_map('trim', explode('=>', $line)));
        if (count($lr) != 2) {
            return null;
        };

        $name = null;
        list($location, $result) = $lr;

        if ($location[0] == '@') {
            $nl = explode(' ', $location, 2);
            $name = substr($nl[0], 1);
            $location = trim($nl[1]);
        };

        return compact('name', 'location', 'result');
    }
}
