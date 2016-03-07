<?php

namespace Yen\Util\Contract;

interface IPluginRegistry
{
    /**
     * @return callable
     */
    public function getPlugin($name);
}
