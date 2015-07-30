<?php

namespace Yen\View;

use Yen\Core;
use Yen\Http;
use Yen\Handler;

abstract class View
{
    protected $dc;

    public function __construct(Core\Contract\IDependencyContainer $dc)
    {
        $this->dc = $dc;
    }

    public function handle($method, Handler\Response $response)
    {
        $mname = $this->defineMethodName($method, $response);

        list($headers, $body) = $this->{$mname}($response->data());
        $code = $response->code();

        return new Http\Response($code, $headers, $body);
    }

    protected function defineMethodName($method, $response)
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
    }

    protected function onRedirect($data)
    {
        $headers = ['Location' => $data];

        return [$headers, null];
    }

    protected function onOk($data)
    {
        $headers = ['Content-Type' => 'text/plain'];
        $body = 'You have missed view method';

        return [$headers, $body];
    }

    protected function onError($data)
    {
        $headers = ['Content-Type' => 'text/plain'];
        $body = is_array($data) ? print_r($data, true) : $data;

        return [$headers, $body];
    }
}
