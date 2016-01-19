<?php

namespace YenMock\View;

class CustomView extends \Yen\View\View
{
    protected function onGetOk($data)
    {
        $headers = ['Content-Type' => 'text/plain'];
        $body = 'get ok';
        return [$headers, $body];
    }

    protected function onGetError($data)
    {
        $headers = ['Content-Type' => 'text/plain'];
        $body = 'get error: ' . $data;
        return [$headers, $body];
    }
}
