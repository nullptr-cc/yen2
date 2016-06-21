<?php

namespace Yen\Router\Exception;

class RouteSyntaxError extends \Exception
{
    public function __construct($index, $line)
    {
        $this->message = sprintf('Routing rule syntax error at #%d "%s"', $index, $line);
    }
}
