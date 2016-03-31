<?php

namespace Yen\Http;

use Yen\Http\Contract\IMessage;

abstract class Message implements IMessage
{
    protected $version;
    protected $headers;
    protected $body;

    public function __construct(
        $version = IMessage::HTTP_VERSION_10,
        array $headers = [],
        $body = ''
    ) {
        $this->version = $version;
        $this->headers = $headers;
        $this->body = $body;
    }

    public function getProtocolVersion()
    {
        return $this->version;
    }

    public function withProtocolVersion($version)
    {
        if ($version != IMessage::HTTP_VERSION_10 && $version != IMessage::HTTP_VERSION_11) {
            throw new \InvalidArgumentException('Invalid HTTP version: ' . $version);
        };

        $clone = clone $this;
        $clone->version = $version;
        return $clone;
    }

    public function getHeaders()
    {
        return $this->headers;
    }

    public function hasHeader($name)
    {
        return array_key_exists($name, $this->headers);
    }

    public function getHeader($name)
    {
        if (!$this->hasHeader($name)) {
            return '';
        };

        return $this->headers[$name];
    }

    public function withHeader($name, $value)
    {
        $headers = $this->headers;
        $headers[$name] = $value;

        $clone = clone $this;
        $clone->headers = $headers;
        return $clone;
    }

    public function withoutHeader($name)
    {
        $headers = $this->headers;
        unset($headers[$name]);

        $clone = clone $this;
        $clone->headers = $headers;
        return $clone;
    }

    public function getBody()
    {
        return $this->body;
    }

    public function withBody($body)
    {
        $clone = clone $this;
        $clone->body = $body;
        return $clone;
    }
}
