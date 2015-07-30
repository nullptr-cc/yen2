<?php

namespace Yen\Handler;

abstract class Response
{
    protected $code;
    protected $data;

    public function __construct($code, $data)
    {
        $this->code = $code;
        $this->data = $data;
    }

    public function isOk()
    {
        return false;
    }

    public function isError()
    {
        return false;
    }

    public function isRedirect()
    {
        return false;
    }

    public function code()
    {
        return $this->code;
    }

    public function data()
    {
        return $this->data;
    }

    public static function name()
    {
        $tmp = explode('\\', static::class);
        return end($tmp);
    }
}
