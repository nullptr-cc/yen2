<?php

namespace YenMock\Handler;

class RevealingHandler extends \Yen\Handler\Handler
{
    public function ok($data)
    {
        return parent::ok($data);
    }

    public function invalidParams($data)
    {
        return parent::invalidParams($data);
    }

    public function forbidden($data)
    {
        return parent::forbidden($data);
    }

    public function notFound($data)
    {
        return parent::notFound($data);
    }

    public function error($data)
    {
        return parent::error($data);
    }
}
