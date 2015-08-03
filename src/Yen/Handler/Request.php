<?php

namespace Yen\Handler;

use Yen\Http;

class Request implements Contract\IRequest
{
    protected $arguments;
    protected $x_arguments;

    public function __construct(Http\Contract\IServerRequest $request, $arguments = [])
    {
        $this->arguments = array_merge(
            $request->getQueryParams(),
            $request->getParsedBody(),
            $arguments
        );

        foreach ($request->getHeaders() as $name => $values) {
            if (strpos($name, 'x-') === 0) {
                $this->x_arguments[$name] = $values;
            };
        };
    }

    public function argument($name, $default = null)
    {
        if (strpos($name, 'x-') === 0) {
            return isset($this->x_arguments[$name]) ? $this->x_arguments[$name] : $default;
        } else {
            return isset($this->arguments[$name]) ? $this->arguments[$name] : $default;
        };
    }
}
