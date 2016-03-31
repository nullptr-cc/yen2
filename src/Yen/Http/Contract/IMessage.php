<?php

namespace Yen\Http\Contract;

interface IMessage
{
    const HTTP_VERSION_10 = '1.0';
    const HTTP_VERSION_11 = '1.1';

    public function getProtocolVersion();
    public function withProtocolVersion($version);

    public function getHeaders();
    public function hasHeader($name);
    public function getHeader($name);
    public function withHeader($name, $value);
    public function withoutHeader($name);

    public function getBody();
    public function withBody($body);
}
