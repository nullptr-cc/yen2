<?php

namespace YenTest\Handler;

use Yen\Handler;

class HandlerTest extends \PHPUnit_Framework_TestCase
{
    use \YenMock\MockServerRequest;
    use \YenMock\MockDC;

    public function testHandle()
    {
        $dc = $this->mockDC();
        $request = new Handler\Request($this->mockServerRequest());
        $handler = new CustomHandler($dc);

        $hr = $handler->handle('GET', $request);
        $this->assertInstanceOf('\Yen\Handler\Response\Ok', $hr);
    }

    public function testHandleInvalidMethod()
    {
        $dc = $this->mockDC();
        $request = new Handler\Request($this->mockServerRequest());
        $handler = new CustomHandler($dc);

        $hr = $handler->handle('POST', $request);
        $this->assertInstanceOf('\Yen\Handler\Response\ErrorInvalidMethod', $hr);
    }
}
