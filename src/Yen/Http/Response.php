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

    public static function ok()
    {
        return new self(IResponse::STATUS_OK);
    }

    public static function movedPermanently()
    {
        return new self(IResponse::STATUS_MOVED_PERMANENTLY);
    }

    public static function movedTemporary()
    {
        return new self(IResponse::STATUS_MOVED_TEMPORARY);
    }

    public static function badRequest()
    {
        return new self(IResponse::STATUS_BAD_REQUEST);
    }

    public static function forbidden()
    {
        return new self(IResponse::STATUS_FORBIDDEN);
    }

    public static function notFound()
    {
        return new self(IResponse::STATUS_NOT_FOUND);
    }

    public static function methodNotAllowed()
    {
        return new self(IResponse::STATUS_METHOD_NOT_ALLOWED);
    }

    public static function internalError()
    {
        return new self(IResponse::STATUS_INTERNAL_ERROR);
    }
}
