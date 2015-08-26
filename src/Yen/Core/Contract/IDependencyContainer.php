<?php

namespace Yen\Core\Contract;

interface IDependencyContainer
{
    public function has($name);
    public function get($name);
}
