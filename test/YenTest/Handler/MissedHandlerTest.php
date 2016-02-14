<?php

namespace YenTest\Handler;

use Yen\Handler;

class MissedHandlerTest extends \PHPUnit_Framework_TestCase
{
    use \YenMock\MockServerRequest;

    public function testHandle()
    {
        $request = $this->mockServerRequest('GET');
        $handler = new Handler\MissedHandler('MissedHandlerClass');

        $hr = $handler->handle($request);
        $this->assertInstanceOf('\Yen\Handler\Response\ErrorNotFound', $hr);
        $this->assertEquals('Handler class MissedHandlerClass not found', $hr->data());
    }
}
