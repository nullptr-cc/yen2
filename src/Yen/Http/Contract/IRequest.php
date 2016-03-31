<?php

namespace Yen\Http\Contract;

interface IRequest extends IMessage
{
    const METHOD_GET = 'GET';
    const METHOD_POST = 'POST';

    public function getRequestTarget();
    public function withRequestTarget($requestTarget);

    public function getMethod();
    public function withMethod($method);

    public function getUri();
    public function withUri(IUri $uri);
}
