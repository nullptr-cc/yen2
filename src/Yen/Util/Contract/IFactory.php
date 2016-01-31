<?php

namespace Yen\Util\Contract;

interface IFactory
{
    public function make($name);
    public function canMake($name);
}
