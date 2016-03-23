<?php

namespace Yen\Handler\Contract;

use Yen\Http\Contract\IServerRequest;

interface IHandler
{
    /**
     * @return string[]
     */
    public function getAllowedMethods();

    /**
     * @return Yen\Http\Contract\IResponse
     */
    public function handle(IServerRequest $request);
}
