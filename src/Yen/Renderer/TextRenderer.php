<?php

namespace Yen\Renderer;

use Yen\Renderer\Contract\IDataRenderer;

class TextRenderer implements IDataRenderer
{
    public function render($data)
    {
        return MimedDocument::createText(print_r($data, true));
    }
}
