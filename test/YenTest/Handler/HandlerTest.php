<?php

namespace YenTest\Handler;

use Yen\Handler;

class HandlerTest extends \PHPUnit_Framework_TestCase
{
    use \YenMock\MockServerRequest;

    public function testHandle()
    {
        $dc = new \Yen\Core\DC();
        $request = new Handler\Request($this->mockServerRequest());
        $handler = new CustomHandler($dc);

        $hr = $handler->handle('GET', $request);
        $this->assertInstanceOf('\Yen\Handler\Response\Ok', $hr);
    }

    public function testHandleInvalidMethod()
    {
        $dc = new \Yen\Core\DC();
        $request = new Handler\Request($this->mockServerRequest());
        $handler = new CustomHandler($dc);

        $hr = $handler->handle('POST', $request);
        $this->assertInstanceOf('\Yen\Handler\Response\ErrorInvalidMethod',  $hr);
    }
}
