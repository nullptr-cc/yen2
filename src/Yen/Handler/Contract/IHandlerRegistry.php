<?php

namespace Yen\Handler\Contract;

interface IHandlerRegistry
{
    /**
     * @param string $name - short or conventional name of handler
     * @return Yen\Handler\Contract\IHandler
     * @throws Yen\Handler\Exception\HandlerNotFound
     */
    public function getHandler($name);

    /**
     * @param string $name - short or conventional name of handler
     * @return bool
     */
    public function hasHandler($name);

    /**
     * @return Yen\Handler\Contract\IHandler
     */
    public function getNotFoundHandler();
}
