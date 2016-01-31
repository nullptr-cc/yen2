<?php

namespace Yen\Util\Contract;

interface IContainer
{
    public function has($name);
    public function get($name);
}
