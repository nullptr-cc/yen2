<?php

namespace Yen\Renderer\Contract;

interface IRenderer
{
    public function mime();
    public function render($data, ...$args);
}
