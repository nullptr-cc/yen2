<?php

namespace Yen\Presenter;

use Yen\Util\CommonRegistry;

class ComponentRegistry extends CommonRegistry implements Contract\IComponentRegistry
{
    public function getComponent($name)
    {
        return $this->get($name);
    }
}
