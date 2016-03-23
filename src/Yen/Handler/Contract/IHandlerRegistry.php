<?php

namespace Yen\Handler\Contract;

interface IHandlerRegistry
{
    /**
     * @return Yen\Handler\Contract\IHandler
     */
    public function getHandler($name);

    /**
     * @return bool
     */
    public function hasHandler($name);
}
