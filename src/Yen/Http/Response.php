<?php

namespace Yen\Http;

use Yen\Http\Contract\IResponse;
use Yen\Http\Contract\IMessage;

class Response extends Message implements IResponse
{
    protected $code;
    protected $reason;

    public function __construct(
        $code = IResponse::STATUS_OK,
        array $headers = [],
        $body = '',
        $reason = '',
        $version = IMessage::HTTP_VERSION_10
    ) {
        parent::__construct($version, $headers, $body);
        $this->code = $code;
        $this->reason = $reason;
    }

    public function getStatusCode()
    {
        return $this->code;
    }

    public function getReasonPhrase()
    {
        return $this->reason;
    }

    public function withStatus($code, $reason = '')
    {
        $clone = clone $this;
        $clone->code = $code;
        $clone->reason = $reason;
        return $clone;
    }
}
