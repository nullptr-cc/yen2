<?php

namespace Yen\Handler;

use Yen\Util\CommonRegistry;

class HandlerRegistry extends CommonRegistry implements Contract\IHandlerRegistry
{
    public function getHandler($name)
    {
        return $this->get($name);
    }
}
