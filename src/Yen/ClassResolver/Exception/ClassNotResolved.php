<?php

namespace Yen\ClassResolver\Exception;

class ClassNotResolved extends \Exception
{
    public function __construct($string, $code = 0, $previous = null)
    {
        $message = sprintf('Class by string "%s" not resolved', $string);
        parent::__construct($message, $code, $previous);
    }
}
