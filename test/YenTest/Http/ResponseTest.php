<?php

namespace YenTest\Http;

use Yen\Http;

class ResponseTest extends \PHPUnit_Framework_TestCase
{
    public function testSimple()
    {
        $response = new Http\Response(200, [], '');
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals([], $response->getHeaders());
        $this->assertEquals('', $response->getBody());
    }
}
