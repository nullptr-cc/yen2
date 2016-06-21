<?php

namespace Yen\Util;

use Yen\Http;
use Yen\Router;

class UrlBuilder
{
    protected $router;
    protected $base_url;

    public function __construct(Router\Contract\IRouter $router, Http\Contract\IUri $base_url = null)
    {
        $this->router = $router;
        $this->base_url = $base_url;
    }

    public function __invoke(Http\Contract\IUri $uri, array $args = [])
    {
        return $this->build($uri, $args);
    }

    public function build(Http\Contract\IUri $uri, array $args = [])
    {
        if ($uri->getScheme() == 'route') {
            $resolved = $this->router->resolve($uri->getPath(), $args);
            $uri = Http\Uri::createFromString($resolved->path());
            $args = $resolved->arguments();
        };

        if (!$uri->getScheme()) {
            $uri = $this->base_url->withPath($uri->getPath());
        };

        return $uri->withQuery(http_build_query($args));
    }
}
