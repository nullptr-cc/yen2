<?php

namespace Yen\Handler\Response;

class Redirect extends \Yen\Handler\Response
{
    public function __construct($url, $permanent = false)
    {
        parent::__construct($permanent ? 301 : 302, $url);
    }

    public function isRedirect()
    {
        return true;
    }
}
