<?php

namespace Yen\Router;

class Route implements Contract\IRoute
{
    protected $entry;
    protected $arguments;

    public function __construct($entry, $arguments)
    {
        $this->entry = $entry;
        $this->arguments = $arguments;
    }

    public function entry()
    {
        return $this->entry;
    }

    public function arguments()
    {
        return $this->arguments;
    }
}
