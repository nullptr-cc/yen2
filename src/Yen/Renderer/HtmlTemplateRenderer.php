<?php

namespace Yen\Renderer;

class HtmlTemplateRenderer extends TemplateRenderer
{
    protected function createDocument($content)
    {
        return MimedDocument::createHtml($content);
    }
}
