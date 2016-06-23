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

    public function testCtorOk()
    {
        $response = Response::ok();
        $this->assertEquals(IResponse::STATUS_OK, $response->getStatusCode());
    }

    public function testCtorMovedPermanently()
    {
        $response = Response::movedPermanently();
        $this->assertEquals(IResponse::STATUS_MOVED_PERMANENTLY, $response->getStatusCode());
    }

    public function testCtorMovedTemporary()
    {
        $response = Response::movedTemporary();
        $this->assertEquals(IResponse::STATUS_MOVED_TEMPORARY, $response->getStatusCode());
    }

    public function testCtorBadRequest()
    {
        $response = Response::badRequest();
        $this->assertEquals(IResponse::STATUS_BAD_REQUEST, $response->getStatusCode());
    }

    public function testCtorForbidden()
    {
        $response = Response::forbidden();
        $this->assertEquals(IResponse::STATUS_FORBIDDEN, $response->getStatusCode());
    }

    public function testCtorNotFound()
    {
        $response = Response::notFound();
        $this->assertEquals(IResponse::STATUS_NOT_FOUND, $response->getStatusCode());
    }

    public function testCtorMethodNotAllowed()
    {
        $response = Response::methodNotAllowed();
        $this->assertEquals(IResponse::STATUS_METHOD_NOT_ALLOWED, $response->getStatusCode());
    }

    public function testCtorInternalError()
    {
        $response = Response::internalError();
        $this->assertEquals(IResponse::STATUS_INTERNAL_ERROR, $response->getStatusCode());
    }
}
