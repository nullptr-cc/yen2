<?php

namespace Yen\Handler\Contract;

use Yen\Http\Contract\IServerRequest;

interface IHandler
{
    public function handle(IServerRequest $request);
}
