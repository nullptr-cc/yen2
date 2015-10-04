<?php

namespace Yen\Core\Contract;

interface IFactory
{
    public function make($name);
    public function canMake($name);
}
