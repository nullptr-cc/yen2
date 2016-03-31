<?php

namespace YenTest\Http;

use Yen\Http\Contract\IRequest;
use Yen\Http\Request;
use Yen\Http\Uri;

class RequestTest extends \PHPUnit_Framework_TestCase
{
    public function testMethod()
    {
        $uri = Uri::createFromString('https://api.example.com/v1/help');

        $request = new Request($uri);
        $this->assertEquals(IRequest::METHOD_GET, $request->getMethod());

        $post_request = $request->withMethod(IRequest::METHOD_POST);
        $this->assertEquals(IRequest::METHOD_POST, $post_request->getMethod());
    }

    public function testRequestTarget()
    {
        $uri = Uri::createFromString('https://api.example.com/v1/help?command=list');

        $help_request = new Request($uri);
        $this->assertEquals('/v1/help?command=list', $help_request->getRequestTarget());

        $list_request = $help_request->withRequestTarget('/v1/list');
        $this->assertEquals('/v1/list', $list_request->getRequestTarget());
    }

    public function testUri()
    {
        $help_uri = Uri::createFromString('https://api.example.com/v1/help');
        $help_request = new Request($help_uri);
        $this->assertEquals($help_uri->__toString(), $help_request->getUri()->__toString());

        $list_uri = Uri::createFromString('https://api.example.com/v1/list');
        $list_request = $help_request->withUri($list_uri);
        $this->assertEquals($list_uri->__toString(), $list_request->getUri()->__toString());
    }
}
