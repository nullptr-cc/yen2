<?php

namespace Yen\Util;

class PluginRegistry extends CommonRegistry implements Contract\IPluginRegistry
{
    public function getPlugin($name)
    {
        return $this->get($name);
    }
}
