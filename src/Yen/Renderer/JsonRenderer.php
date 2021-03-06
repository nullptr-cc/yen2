<?php

namespace Yen\Renderer;

use Yen\Renderer\Contract\IDataRenderer;

class JsonRenderer implements IDataRenderer
{
    public function render($data)
    {
        return MimedDocument::createJson(json_encode($data));
    }
}
