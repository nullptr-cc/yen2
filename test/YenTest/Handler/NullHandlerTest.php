<?php

namespace YenTest\Handler;

use Yen\Handler;

class NullHandlerTest extends \PHPUnit_Framework_TestCase
{
    use MockServerRequest;

    public function testHandle()
    {
        $request = new Handler\Request($this->mockServerRequest());
        $handler = new Handler\NullHandler('message');

        $hr = $handler->handle('GET', $request);
        $this->assertInstanceOf('\Yen\Handler\Response\ErrorNotFound', $hr);
        $this->assertEquals('message', $hr->data());
    }
}
