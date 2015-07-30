<?php

namespace Yen\Core\Contract;

interface IDependencyContainer
{
    public function __call($method, $args);
}
