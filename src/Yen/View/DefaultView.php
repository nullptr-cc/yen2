<?php

namespace Yen\View;

class DefaultView extends View
{
    protected function onOk($data)
    {
        return $this->makeDefaultResponse($data);
    }

    protected function onError($data)
    {
        return $this->makeDefaultResponse($data);
    }

    protected function makeDefaultResponse($data)
    {
        $headers = ['Content-Type' => 'text/plain'];
        $body = is_array($data) ? print_r($data, true) : $data;

        return [$headers, $body];
    }
}
