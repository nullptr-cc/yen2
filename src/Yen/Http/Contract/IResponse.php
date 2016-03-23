<?php

namespace Yen\Http\Contract;

interface IResponse
{
    const STATUS_OK = 200;

    const STATUS_MOVED_PERMANENTLY = 301;
    const STATUS_MOVED_TEMPORARY = 302;

    const STATUS_BAD_REQUEST = 400;
    const STATUS_FORBIDDEN = 403;
    const STATUS_NOT_FOUND = 404;
    const STATUS_METHOD_NOT_ALLOWED = 405;

    const STATUS_INTERNAL_ERROR = 500;

    public function getStatusCode();
    public function getBody();
    public function getHeaders();

    public function withBody($body);
}
