<?php

namespace Yen\Core\Contract;

interface IContainer
{
    public function has($name);
    public function get($name);
}
