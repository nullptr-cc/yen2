<?php

namespace Yen\Core;

class Exception extends \Exception
{
    public function __construct($msg = '', $code = 0, $prev = null, $file = null, $line = null)
    {
        parent::__construct($msg, $code, $prev);

        if ($file !== null && $line !== null) {
            $this->file = $file;
            $this->line = $line;
        };
    }
}
