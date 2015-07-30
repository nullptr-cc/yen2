<?php

namespace Yen\Handler\Contract;

interface IRequest
{
    public function argument($name, $default = null);
}
