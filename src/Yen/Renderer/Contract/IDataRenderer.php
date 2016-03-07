<?php

namespace Yen\Renderer\Contract;

interface IDataRenderer
{
    public function mime();
    public function render($data);
}
