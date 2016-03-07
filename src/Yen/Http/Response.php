<?php

namespace Yen\Http;

class Response implements Contract\IResponse
{
    protected $code;
    protected $headers;
    protected $body;

    public function __construct($code, $headers, $body)
    {
        $this->code = $code;
        $this->headers = $headers;
        $this->body = $body;
    }

    public function getStatusCode()
    {
        return $this->code;
    }

    public function getHeaders()
    {
        return $this->headers;
    }

    public function getBody()
    {
        return $this->body;
    }

    public function withBody($body)
    {
        return new self($this->code, $this->headers, $body);
    }
}
