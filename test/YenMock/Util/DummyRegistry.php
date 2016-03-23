<?php

namespace YenMock\Util;

use Yen\Util\CommonRegistry;

class DummyRegistry extends CommonRegistry
{
    public function get($name)
    {
        return parent::get($name);
    }

    public function has($name)
    {
        return parent::has($name);
    }
}
