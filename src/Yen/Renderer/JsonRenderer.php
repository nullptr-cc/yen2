<?php

namespace Yen\Renderer;

class JsonRenderer implements Contract\IDataRenderer
{
    public function mime()
    {
        return 'application/json';
    }

    public function render($data)
    {
        return json_encode($data);
    }
}
