<?php

namespace Yen\Renderer;

class HtmlRenderer extends TemplateRenderer
{
    public function mime()
    {
        return 'text/html';
    }
}
