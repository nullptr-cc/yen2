<?php

namespace Yen\View;

use Yen\Util;

class ViewRegistry extends Util\FactoryRegistry implements Contract\IViewRegistry
{
    public function getView($name)
    {
        return $this->get($name);
    }
}
