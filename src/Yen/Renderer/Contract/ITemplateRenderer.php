<?php

namespace Yen\Renderer\Contract;

interface ITemplateRenderer
{
    public function mime();
    public function render($template, $data);
}
