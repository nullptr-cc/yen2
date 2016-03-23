<?php

namespace Yen\Util\Contract;

interface IClassResolver
{
    /**
     * @return string
     */
    public function resolve($string);
}
