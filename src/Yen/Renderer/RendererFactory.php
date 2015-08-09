<?php

namespace Yen\Renderer;

class RendererFactory implements Contract\IRendererFactory
{
    public function makeDefaultRenderer()
    {
        return new DefaultRenderer();
    }

    public function makeJsonRenderer()
    {
        return new JsonRenderer();
    }

    public function makeHtmlRenderer()
    {
        return new DefaultRenderer();
    }
}
