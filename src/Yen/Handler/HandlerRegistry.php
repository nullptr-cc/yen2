<?php

namespace Yen\Handler;

use Yen\Util\CommonRegistry;
use Yen\Handler\Contract\IHandlerRegistry;

class HandlerRegistry extends CommonRegistry implements IHandlerRegistry
{
    /**
     * @return Yen\Handler\Contract\IHandler
     */
    public function getHandler($name)
    {
        return $this->get($name);
    }

    /**
     * @return bool
     */
    public function hasHandler($name)
    {
        return $this->has($name);
    }

    /**
     * @return Throwable
     */
    protected function createInvalidNameException($name)
    {
        return new HandlerNotFoundException($name);
    }
}
