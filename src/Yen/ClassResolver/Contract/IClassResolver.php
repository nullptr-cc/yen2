<?php

namespace Yen\ClassResolver\Contract;

interface IClassResolver
{
    /**
     * @param string
     * @return string
     * @throws Yen\Util\ClassNotResolved
     */
    public function resolve($string);
}
