<?php

namespace Yen\Http;

use Yen\Http\Contract\IUri;

class Uri implements IUri, \JsonSerializable
{
    private $scheme;
    private $userinfo;
    private $host;
    private $port;
    private $path;
    private $query;
    private $fragment;

    public function __construct(array $parts = [])
    {
        isset($parts['scheme']) && $this->scheme = $parts['scheme'];
        isset($parts['user']) && $this->userinfo = $parts['user'] . (isset($parts['pass']) ? ':' . $parts['pass'] : '');
        isset($parts['host']) && $this->host = $parts['host'];
        isset($parts['port']) && $this->port = $parts['port'];
        $this->path = isset($parts['path']) ? $parts['path'] : '/';
        isset($parts['query']) && $this->query = $parts['query'];
        isset($parts['fragment']) && $this->fragment = $parts['fragment'];
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
        $authority = $this->host;

        if ($this->userinfo) {
            $authority = $this->userinfo . '@' . $authority;
        };

        if ($this->port) {
            $authority .= ':' . $this->port;
        };

        return $authority;
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
        $clone = clone $this;
        $clone->scheme = $scheme;
        return $clone;
    }

    public function withUserInfo($user, $password = null)
    {
        $clone = clone $this;
        $clone->userinfo = $user . ($password ? ':' . $password : '');
        return $clone;
    }

    public function withHost($host)
    {
        $clone = clone $this;
        $clone->host = $host;
        return $clone;
    }

    public function withPort($port)
    {
        $clone = clone $this;
        $clone->port = $port;
        return $clone;
    }

    public function withPath($path)
    {
        $clone = clone $this;
        $clone->path = $path;
        return $clone;
    }

    public function withQuery($query)
    {
        $clone = clone $this;
        $clone->query = $query;
        return $clone;
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
        $clone = clone $this;
        $clone->fragment = $fragment;
        return $clone;
    }

    public function __toString()
    {
        $str = [];
        $this->scheme && $str[] = $this->scheme . '://';
        $authority = $this->getAuthority();
        $authority && $str[] = $authority;
        $str[] = $this->path;
        $this->query && $str[] = '?' . $this->query;
        $this->fragment && $str[] = '#' . $this->fragment;

        return implode('', $str);
    }

    public function jsonSerialize()
    {
        return $this->__toString();
    }
}
