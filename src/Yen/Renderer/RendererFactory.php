<?php

namespace Yen\Renderer;

class RendererFactory
{
    public function make($content_type = null)
    {
        switch ($content_type) {
            case 'json':
                return new JsonRenderer();
                break;
            default:
                return new DefaultRenderer();
                break;
        };
    }
}
