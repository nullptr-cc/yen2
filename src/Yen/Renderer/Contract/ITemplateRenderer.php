<?php

namespace Yen\Renderer\Contract;

interface ITemplateRenderer
{
    /**
     * @return MimedDocument
     */
    public function render($template, $data);
}
