<?php

namespace Yen\HttpClient\Contract;

use Yen\Http\Contract\IRequest;

interface IHttpClient
{
    /**
     * @return IResponse
     */
    public function send(IRequest $request);
}
