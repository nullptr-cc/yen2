<?php

namespace YenMock\Util;

use Yen\Util\CommonRegistry;

class DummyRegistry extends CommonRegistry
{
    public function get($name)
    {
        return parent::get($name);
    }
}
