<?php

namespace Yen\Handler\Contract;

interface IHandler
{
    public function handle(\Yen\Http\Contract\IServerRequest $request);
}
