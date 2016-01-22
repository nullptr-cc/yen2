<?php

namespace YenTest\Handler;

use Yen\Handler;

class MissedHandlerTest extends \PHPUnit_Framework_TestCase
{
    use \YenMock\MockServerRequest;

    public function testHandle()
    {
        $request = new Handler\Request($this->mockServerRequest());
        $handler = new Handler\MissedHandler('MissedHandlerClass');

        $hr = $handler->handle('GET', $request);
        $this->assertInstanceOf('\Yen\Handler\Response\ErrorNotFound', $hr);
        $this->assertEquals('Handler class MissedHandlerClass not found', $hr->data());
    }
}
