<?php

namespace Yen\Http;

class Uri implements Contract\IUri, \JsonSerializable
{
    protected $scheme;
    protected $userinfo;
    protected $host;
    protected $port;
    protected $path;
    protected $query;
    protected $fragment;
    protected $authority;

    public function __construct(array $parts = [])
    {
        isset($parts['scheme']) && $this->scheme = $parts['scheme'];
        isset($parts['user']) && $this->userinfo = $parts['user'] . (isset($parts['pass']) ? ':' . $parts['pass'] : '');
        isset($parts['host']) && $this->host = $parts['host'];
        isset($parts['port']) && $this->port = $parts['port'];
        $this->path = isset($parts['path']) ? $parts['path'] : '/';
        isset($parts['query']) && $this->query = $parts['query'];
        isset($parts['fragment']) && $this->fragment = $parts['fragment'];

        $this->authority = $this->host;
        $this->userinfo && $this->authority = $this->userinfo . '@' . $this->authority;
        $this->port && $this->authority .= ':' . $this->port;
    }

    public static function createFromString($string)
    {
        return new self(parse_url($string));
    }

    public function getScheme()
    {
        return $this->scheme;
    }

    public function getAuthority()
    {
        return $this->authority;
    }

    public function getUserInfo()
    {
        return $this->userinfo;
    }

    public function getHost()
    {
        return $this->host;
    }

    public function getPort()
    {
        return $this->port;
    }

    public function getPath()
    {
        return $this->path;
    }

    public function getQuery()
    {
        return $this->query;
    }

    public function getFragment()
    {
        return $this->fragment;
    }

    public function withScheme($scheme)
    {
        return new self(array_merge($this->parts(), ['scheme' => $scheme]));
    }

    public function withUserInfo($user, $password = null)
    {
        return new self(array_merge($this->parts(), ['user' => $user, 'pass' => $password]));
    }

    public function withHost($host)
    {
        return new self(array_merge($this->parts(), ['host' => $host]));
    }

    public function withPort($port)
    {
        return new self(array_merge($this->parts(), ['port' => $port]));
    }

    public function withPath($path)
    {
        return new self(array_merge($this->parts(), ['path' => $path]));
    }

    public function withQuery($query)
    {
        return new self(array_merge($this->parts(), ['query' => $query]));
    }

    public function withJoinedQuery(array $args)
    {
        parse_str($this->query, $parsed);
        $new_args = array_merge($parsed, $args);
        $new_query = http_build_query($new_args);

        return $this->withQuery($new_query);
    }

    public function withFragment($fragment)
    {
        return new self(array_merge($this->parts(), ['fragment' => $fragment]));
    }

    public function __toString()
    {
        $str = [];
        $this->scheme && $str[] = $this->scheme . '://';
        $this->authority && $str[] = $this->authority;
        $str[] = $this->path;
        $this->query && $str[] = '?' . $this->query;
        $this->fragment && $str[] = '#' . $this->fragment;

        return implode('', $str);
    }

    public function jsonSerialize()
    {
        return $this->__toString();
    }

    private function parts()
    {
        return [
            'scheme' => $this->scheme,
            'userinfo' => $this->userinfo,
            'host' => $this->host,
            'port' => $this->port,
            'path' => $this->path,
            'query' => $this->query,
            'fragment' => $this->fragment
        ];
    }
}
