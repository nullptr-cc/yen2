<?php

namespace Yen\Handler\Contract;

interface IHandlerFactory
{
    /**
     * @return Yen\Handler\Contract\IHandler
     * @throws Yen\Handler\Exception\HandlerNotMaked
     */
    public function makeHandler($name);
}
