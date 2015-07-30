<?php

namespace Yen\Renderer;

class DefaultRenderer implements Contract\IRenderer
{
    public function mime()
    {
        return 'text/plain';
    }

    public function render($data, ...$args)
    {
        return [
            ['Content-Type' => $this->mime()],
            print_r($data, true)
        ];
    }
}
