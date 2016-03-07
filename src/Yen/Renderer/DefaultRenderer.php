<?php

namespace Yen\Renderer;

class DefaultRenderer implements Contract\IDataRenderer
{
    public function mime()
    {
        return 'text/plain';
    }

    public function render($data)
    {
        return print_r($data, true);
    }
}
