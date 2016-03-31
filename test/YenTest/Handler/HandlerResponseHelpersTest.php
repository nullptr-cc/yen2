<?php

namespace YenTest\Handler;

use Yen\Http\ServerRequest;
use Yen\Http\Contract\IResponse;
use YenMock\Handler\HelpersHandler;

class HandlerResponseHelpersTest extends \PHPUnit_Framework_TestCase
{
    public function testOk()
    {
        $handler = new HelpersHandler();
        $request = ServerRequest::createFromGlobals()->withQueryParams(['r' => 'ok']);

        $response = $handler->handle($request);

        $this->assertInstanceOf(IResponse::class, $response);
        $this->assertEquals(IResponse::STATUS_OK, $response->getStatusCode());
        $this->assertEquals(['Content-Type' => 'text/plain'], $response->getHeaders());
        $this->assertEquals('test', $response->getBody());
    }

    public function testBadRequest()
    {
        $handler = new HelpersHandler();
        $request = ServerRequest::createFromGlobals()->withQueryParams(['r' => 'bad_request']);

        $response = $handler->handle($request);

        $this->assertInstanceOf(IResponse::class, $response);
        $this->assertEquals(IResponse::STATUS_BAD_REQUEST, $response->getStatusCode());
        $this->assertEquals(['Content-Type' => 'text/plain'], $response->getHeaders());
        $this->assertEquals('test', $response->getBody());
    }

    public function testForbidden()
    {
        $handler = new HelpersHandler();
        $request = ServerRequest::createFromGlobals()->withQueryParams(['r' => 'forbidden']);

        $response = $handler->handle($request);

        $this->assertInstanceOf(IResponse::class, $response);
        $this->assertEquals(IResponse::STATUS_FORBIDDEN, $response->getStatusCode());
        $this->assertEquals(['Content-Type' => 'text/plain'], $response->getHeaders());
        $this->assertEquals('test', $response->getBody());
    }

    public function testNotFound()
    {
        $handler = new HelpersHandler();
        $request = ServerRequest::createFromGlobals()->withQueryParams(['r' => 'not_found']);

        $response = $handler->handle($request);

        $this->assertInstanceOf(IResponse::class, $response);
        $this->assertEquals(IResponse::STATUS_NOT_FOUND, $response->getStatusCode());
        $this->assertEquals(['Content-Type' => 'text/plain'], $response->getHeaders());
        $this->assertEquals('test', $response->getBody());
    }

    public function testError()
    {
        $handler = new HelpersHandler();
        $request = ServerRequest::createFromGlobals()->withQueryParams(['r' => 'error']);

        $response = $handler->handle($request);

        $this->assertInstanceOf(IResponse::class, $response);
        $this->assertEquals(IResponse::STATUS_INTERNAL_ERROR, $response->getStatusCode());
        $this->assertEquals(['Content-Type' => 'text/plain'], $response->getHeaders());
        $this->assertEquals('test', $response->getBody());
    }

    public function testRedirectPermanent()
    {
        $handler = new HelpersHandler();
        $request = ServerRequest::createFromGlobals()->withQueryParams(['r' => 'redirect_perm']);

        $response = $handler->handle($request);

        $this->assertInstanceOf(IResponse::class, $response);
        $this->assertEquals(IResponse::STATUS_MOVED_PERMANENTLY, $response->getStatusCode());
        $this->assertEquals(['Location' => '/test'], $response->getHeaders());
        $this->assertEquals('', $response->getBody());
    }

    public function testRedirectTemporary()
    {
        $handler = new HelpersHandler();
        $request = ServerRequest::createFromGlobals()->withQueryParams(['r' => 'redirect_temp']);

        $response = $handler->handle($request);

        $this->assertInstanceOf(IResponse::class, $response);
        $this->assertEquals(IResponse::STATUS_MOVED_TEMPORARY, $response->getStatusCode());
        $this->assertEquals(['Location' => '/test'], $response->getHeaders());
        $this->assertEquals('', $response->getBody());
    }
}
