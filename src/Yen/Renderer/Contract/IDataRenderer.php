<?php

namespace Yen\Renderer\Contract;

interface IDataRenderer
{
    /**
     * @return MimedDocument
     */
    public function render($data);
}
