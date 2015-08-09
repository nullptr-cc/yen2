<?php

namespace Yen\Renderer\Contract;

interface IRendererFactory
{
    public function makeDefaultRenderer();
    public function makeJsonRenderer();
    public function makeHtmlRenderer();
}
