<?php

namespace YenTest\Handler;

use Yen\Handler;
use YenMock\Handler\CustomHandler;
use YenMock\Handler\RevealingHandler;

class HandlerTest extends \PHPUnit_Framework_TestCase
{
    use \YenMock\MockServerRequest;

    public function testHandle()
    {
        $request = new Handler\Request($this->mockServerRequest());
        $handler = new CustomHandler();

        $hr = $handler->handle('GET', $request);
        $this->assertInstanceOf('\Yen\Handler\Response\Ok', $hr);
    }

    public function testHandleInvalidMethod()
    {
        $request = new Handler\Request($this->mockServerRequest());
        $handler = new CustomHandler();

        $hr = $handler->handle('POST', $request);
        $this->assertInstanceOf('\Yen\Handler\Response\ErrorInvalidMethod', $hr);
    }

    public function testShortcuts()
    {
        $handler = new RevealingHandler();

        $resp = $handler->ok('data-ok');
        $this->assertInstanceOf('\Yen\Handler\Response\Ok', $resp);
        $this->assertEquals('data-ok', $resp->data());

        $resp = $handler->invalidParams('data-invalid-params');
        $this->assertInstanceOf('\Yen\Handler\Response\ErrorInvalidParams', $resp);
        $this->assertEquals('data-invalid-params', $resp->data());

        $resp = $handler->forbidden('data-forbidden');
        $this->assertInstanceOf('\Yen\Handler\Response\ErrorForbidden', $resp);
        $this->assertEquals('data-forbidden', $resp->data());

        $resp = $handler->notFound('data-not-found');
        $this->assertInstanceOf('\Yen\Handler\Response\ErrorNotFound', $resp);
        $this->assertEquals('data-not-found', $resp->data());

        $resp = $handler->error('data-error');
        $this->assertInstanceOf('\Yen\Handler\Response\ErrorInternal', $resp);
        $this->assertEquals('data-error', $resp->data());
    }
}
