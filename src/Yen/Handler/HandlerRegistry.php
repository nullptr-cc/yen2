<?php

namespace Yen\Handler;

use Yen\Util;

class HandlerRegistry extends Util\FactoryRegistry implements Contract\IHandlerRegistry
{
    public function getHandler($name)
    {
        return $this->get($name);
    }
}
