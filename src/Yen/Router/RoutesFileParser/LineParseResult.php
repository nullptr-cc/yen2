<?php

namespace Yen\Router\RoutesFileParser;

class LineParseResult
{
    private $name;
    private $location;
    private $result;

    public function __construct($name, $location, $result)
    {
        $this->name = $name;
        $this->location = $location;
        $this->result = $result;
    }

    public function name()
    {
        return $this->name;
    }

    public function location()
    {
        return $this->location;
    }

    public function result()
    {
        return $this->result;
    }
}
