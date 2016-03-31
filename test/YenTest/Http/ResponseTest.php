<?php

namespace YenTest\Http;

use Yen\Http\Contract\IResponse;
use Yen\Http\Response;

class ResponseTest extends \PHPUnit_Framework_TestCase
{
    public function testSimple()
    {
        $response = new Response(IResponse::STATUS_OK, [], '');
        $this->assertEquals(IResponse::STATUS_OK, $response->getStatusCode());
        $this->assertEquals([], $response->getHeaders());
        $this->assertEquals('', $response->getBody());
    }

    public function testWithBody()
    {
        $response = new Response(IResponse::STATUS_OK, [], '');
        $this->assertEquals('', $response->getBody());

        $response = $response->withBody('changed body');
        $this->assertEquals('changed body', $response->getBody());
    }

    public function testStatus()
    {
        $response = new Response();
        $this->assertEquals(IResponse::STATUS_OK, $response->getStatusCode());

        $bad = $response->withStatus(IResponse::STATUS_BAD_REQUEST, 'You are Wrong');
        $this->assertEquals(IResponse::STATUS_BAD_REQUEST, $bad->getStatusCode());
        $this->assertEquals('You are Wrong', $bad->getReasonPhrase());
    }
}
