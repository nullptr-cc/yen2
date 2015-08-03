<?php

namespace Yen\Http;

class ServerRequest implements Contract\IServerRequest
{
    protected $env;
    protected $method;
    protected $target;
    protected $headers;
    protected $query;
    protected $body;
    protected $cookies;
    protected $files;
    protected $uri;

    public function __construct(array $env = [], array $query = [], array $body = [], array $cookies = [], array $files = [])
    {
        $this->env = $env;
        $this->method = isset($env['REQUEST_METHOD']) ? $env['REQUEST_METHOD'] : null;
        $this->target = isset($env['REQUEST_URI']) ? $env['REQUEST_URI'] : '/';
        $this->query = $query;
        $this->body = $body;
        $this->cookies = $cookies;
        $this->files = static::populateFiles($files);

        $this->headers = [];
        foreach ($env as $key => $value) {
            if (strpos($key, 'HTTP_') === 0) {
                $nkey = strtolower(str_replace('_', '-', substr($key, 5)));
                $this->headers[$nkey] = $value;
            };
        };

        if (strpos('?', $this->target) !== false) {
            list($path, $query) = explode('?', $this->target);
            $this->uri = new Uri(['path' => $path, 'query' => $query]);
        } else {
            $this->uri = new Uri(['path' => $this->target]);
        };
    }

    public function getMethod()
    {
        return $this->method;
    }

    public function getRequestTarget()
    {
        return $this->target;
    }

    public function getUri()
    {
        return $this->uri;
    }

    public function getServerParams()
    {
        return $this->env;
    }

    public function getCookieParams()
    {
        return $this->cookies;
    }

    public function getQueryParams()
    {
        return $this->query;
    }

    public function getUploadedFiles()
    {
        return $this->files;
    }

    public function getParsedBody()
    {
        return $this->body;
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
        return $this->hasHeader($name) ? $this->headers[$name] : [];
    }

    public function getHeaderLine($name)
    {
        return $this->hasHeader($name) ? implode(',', $this->headers[$name]) : '';
    }

    protected static function populateFiles(array $files)
    {
        $create = function($inf) {
            return new UploadedFile($inf['error'], $inf['name'], $inf['size'], $inf['type'], $inf['tmp_name']);
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
                $retutn[$pname][] = $create($finfo);
            };
        };

        return $return;
    }
}
