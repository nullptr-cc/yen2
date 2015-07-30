<?php

namespace Yen\Renderer;

class JsonRenderer implements Contract\IRenderer
{
    public function mime()
    {
        return 'application/json';
    }

    public function render($data, ...$args)
    {
        return [
            ['Content-Type' => $this->mime()],
            json_encode($data)
        ];
    }
}
