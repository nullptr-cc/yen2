<?php

namespace Yen\View;

use Yen\Core;
use Yen\Http;
use Yen\Handler;

abstract class View implements Contract\IView
{
    protected $dc;

    public function __construct(Core\Contract\IContainer $dc)
    {
        $this->dc = $dc;
    }

    public function present($method, Handler\Contract\IResponse $response)
    {
        $mname = $this->resolveMethodName($method, $response);

        if (null === $mname) {
            list($headers, $body) = $this->makeMissedViewMethodResponse($method, $response);
        } else {
            list($headers, $body) = $this->{$mname}($response->data());
        };

        $code = $response->code();

        return new Http\Response($code, $headers, $body);
    }

    protected function resolveMethodName($method, $response)
    {
        if ($response->isRedirect()) {
            return 'onRedirect';
        };

        $m = ucfirst(strtolower($method));

        if ($response->isOk()) {
            $names = ['on' . $m . 'Ok', 'onOk'];
        } else {
            $names = ['on' . $m . $response::name(), 'on' . $m . 'Error', 'onError'];
        };

        foreach ($names as $name) {
            if (method_exists($this, $name)) {
                return $name;
            };
        };

        return null;
    }

    protected function onRedirect($data)
    {
        $headers = ['Location' => $data];

        return [$headers, null];
    }

    protected function makeMissedViewMethodResponse($method, $response)
    {
        $headers = ['Content-Type' => 'text/plain'];
        $body = sprintf('You have missed view method for: %s, %s', $method, $response::name());

        return [$headers, $body];
    }
}
