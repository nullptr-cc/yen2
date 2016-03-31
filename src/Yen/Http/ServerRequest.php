<?php

namespace Yen\Http;

use Yen\Http\Contract\IServerRequest;
use Yen\Http\Contract\IMessage;
use Yen\Http\Contract\IRequest;
use Yen\Http\Contract\IUri;

class ServerRequest extends Request implements IServerRequest
{
    protected $env;
    protected $query_params;
    protected $parsed_body;
    protected $cookies;
    protected $uploaded_files;

    public function __construct(
        array $env,
        array $query_params,
        array $parsed_body,
        array $cookies,
        array $uploaded_files,
        IUri $uri,
        $method = IRequest::METHOD_GET,
        $target = '',
        array $headers = [],
        $body = '',
        $version = IMessage::HTTP_VERSION_10
    ) {
        parent::__construct($uri, $method, $target, $headers, $body, $version);
        $this->env = $env;
        $this->query_params = $query_params;
        $this->parsed_body = $parsed_body;
        $this->cookies = $cookies;
        $this->uploaded_files = $uploaded_files;
    }

    public static function createFromGlobals(
        array $env = [],
        array $query = [],
        array $body = [],
        array $cookies = [],
        array $files = []
    ) {
        $method = isset($env['REQUEST_METHOD']) ? $env['REQUEST_METHOD'] : IRequest::METHOD_GET;
        $target = isset($env['REQUEST_URI']) ? $env['REQUEST_URI'] : '/';

        $headers = [];
        foreach ($env as $key => $value) {
            if (strpos($key, 'HTTP_') === 0) {
                $nkey = strtolower(str_replace('_', '-', substr($key, 5)));
                $headers[$nkey] = $value;
            };
        };

        $uri_args = [
            'scheme' => isset($env['REQUEST_SCHEME']) ? $env['REQUEST_SCHEME'] : null,
            'host' => isset($env['HTTP_HOST']) ? $env['HTTP_HOST'] : null,
        ];
        if (strpos($target, '?') !== false) {
            list($p, $q) = explode('?', $target, 2);
            $uri_args += ['path' => $p, 'query' => $q];
        } else {
            $uri_args += ['path' => $target];
        };
        $uri = new Uri($uri_args);

        $version = IMessage::HTTP_VERSION_10;
        if (isset($env['SERVER_PROTOCOL'])) {
            $version = substr($env['SERVER_PROTOCOL'], 5);
        };

        $uploaded_files = self::fillFiles($files);

        return new self(
            $env,
            $query,
            $body,
            $cookies,
            $uploaded_files,
            $uri,
            $method,
            $target,
            $headers,
            '', // TODO: stream from php://input
            $version
        );
    }

    public function getServerParams()
    {
        return $this->env;
    }

    public function getCookieParams()
    {
        return $this->cookies;
    }

    public function withCookieParams(array $cookies)
    {
        $clone = clone $this;
        $clone->cookies = $cookies;
        return $clone;
    }

    public function getQueryParams()
    {
        return $this->query_params;
    }

    public function withQueryParams(array $params)
    {
        $clone = clone $this;
        $clone->query_params = $params;
        return $clone;
    }

    public function withJoinedQueryParams(array $params)
    {
        $clone = clone $this;
        $clone->query_params = array_merge($this->query_params, $params);
        return $clone;
    }

    public function getUploadedFiles()
    {
        return $this->uploaded_files;
    }

    public function withUploadedFiles(array $files)
    {
        $clone = clone $this;
        $clone->uploaded_files = $files;
        return $clone;
    }

    public function getParsedBody()
    {
        return $this->parsed_body;
    }

    public function withParsedBody($data)
    {
        $clone = clone $this;
        $clone->parsed_body = $data;
        return $clone;
    }

    public static function fillFiles(array $files)
    {
        $create = function ($inf) {
            return new UploadedFile($inf['error'], $inf['size'], $inf['name'], $inf['type'], $inf['tmp_name']);
        };

        $return = [];

        foreach ($files as $pname => $pinfo) {
            if (!is_array($pinfo['name'])) {
                $return[$pname][] = $create($pinfo);
                continue;
            };
            $_ = array_fill(0, count($pinfo['name']), []);
            foreach ($pinfo as $fk => $fv) {
                foreach ($fv as $i => $v) {
                    $_[$i][$fk] = $v;
                };
            };
            foreach ($_ as $finfo) {
                $return[$pname][] = $create($finfo);
            };
        };

        return $return;
    }
}
