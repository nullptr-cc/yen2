<?php

namespace YenMock\View;

class NotFoundView extends \Yen\View\View
{
    protected function onGetErrorNotFound($data)
    {
        $headers = ['Content-Type' => 'text/plain'];
        $body = 'get error not found: ' . $data;
        return [$headers, $body];
    }
}
