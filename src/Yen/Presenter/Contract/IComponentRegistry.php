<?php

namespace Yen\Presenter\Contract;

interface IComponentRegistry
{
    /**
     * @return callable
     */
    public function getComponent($cname);
}
