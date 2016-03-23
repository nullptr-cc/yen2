<?php

namespace Yen\Http\Contract;

interface IRequest
{
    const METHOD_GET = 'GET';
    const METHOD_POST = 'POST';

    public function withMethod($method);
}
