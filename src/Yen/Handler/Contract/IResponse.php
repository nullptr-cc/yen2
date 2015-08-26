<?php

namespace Yen\Handler\Contract;

interface IResponse
{
    public function isOk();
    public function isError();
    public function isRedirect();
    public function code();
    public function data();
    public static function name();
}
