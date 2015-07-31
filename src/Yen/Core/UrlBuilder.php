<?php

namespace Yen\Core;

use Yen\Http;

class UrlBuilder
{
    protected $dc;
    protected $base_url;

    public function __construct(Contract\IDependencyContainer $dc, Http\Contract\IUri $base_url = null)
    {
        $this->dc = $dc;
        $this->base_url = $base_url;
    }

    public function __invoke($uri, array $args = [])
    {
        return $this->build($uri, $args);
    }

    public function build($uri, array $args = [])
    {
        if (strpos($uri, 'route:') === 0) {
            $resolved = $this->dc->router()->resolve(substr($uri, 6), $args);
            $uri = $resolved->uri;
            $args = $resolved->args;
        };

        $qs = count($args) ? http_build_query($args) : '';

        if (strpos($uri, '://') !== false) {
            return Http\Uri::createFromString($uri)->withQuery($qs);
        } else {
            return $this->base_url->withPath($uri)->withQuery($qs);
        };
    }
}
