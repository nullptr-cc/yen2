<?php

namespace YenMock\Handler;

use Yen\Handler\Contract\IHandler;
use Yen\Http\Contract\IServerRequest;
use Yen\Http\Contract\IRequest;
use Yen\Http\Response;

class CustomHandler implements IHandler
{
    public function getAllowedMethods()
    {
        return [IRequest::METHOD_GET];
    }

    public function handle(IServerRequest $request)
    {
        return new Response(200, [], 'ok');
    }
}
