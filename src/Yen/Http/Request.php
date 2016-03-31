<?php

namespace Yen\Http;

use Yen\Http\Contract\IMessage;
use Yen\Http\Contract\IRequest;
use Yen\Http\Contract\IUri;

class Request extends Message implements IRequest
{
    protected $uri;
    protected $method;
    protected $target;

    public function __construct(
        IUri $uri,
        $method = IRequest::METHOD_GET,
        $target = '',
        array $headers = [],
        $body = '',
        $version = IMessage::HTTP_VERSION_10
    ) {
        parent::__construct($version, $headers, $body);
        $this->uri = $uri;
        $this->method = $method;
        $this->target = $target ?: $this->formTarget($uri);
    }

    public function getMethod()
    {
        return $this->method;
    }

    public function withMethod($method)
    {
        $clone = clone $this;
        $clone->method = $method;
        return $clone;
    }

    public function getRequestTarget()
    {
        return $this->target;
    }

    public function withRequestTarget($target)
    {
        $clone = clone $this;
        $clone->target = $target;
        return $clone;
    }

    public function getUri()
    {
        return $this->uri;
    }

    public function withUri(IUri $uri)
    {
        $clone = clone $this;
        $clone->uri = $uri;
        return $clone;
    }

    protected function formTarget(IUri $uri)
    {
        $target = $uri->getPath();
        if ($query = $uri->getQuery()) {
            $target .= '?' . $query;
        };
        return $target;
    }
}
