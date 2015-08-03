<?php

namespace YenTest\Handler;

use Yen\Handler;

class ResponsesTest extends \PHPUnit_Framework_TestCase
{
    public function testResponseOk()
    {
        $r = new Handler\Response\Ok('ok');
        $this->assertTrue($r->isOk());
        $this->assertFalse($r->isError());
        $this->assertFalse($r->isRedirect());
        $this->assertEquals(200, $r->code());
        $this->assertEquals('ok', $r->data());
    }

    public function testResponseRedirect()
    {
        $r = new Handler\Response\Redirect('scheme://path');
        $this->assertTrue($r->isRedirect());
        $this->assertFalse($r->isOk());
        $this->assertFalse($r->isError());
        $this->assertEquals(302, $r->code());
        $this->assertEquals('scheme://path', $r->data());

        $r = new Handler\Response\Redirect('scheme://path', true);
        $this->assertTrue($r->isRedirect());
        $this->assertFalse($r->isOk());
        $this->assertFalse($r->isError());
        $this->assertEquals(301, $r->code());
        $this->assertEquals('scheme://path', $r->data());
    }

    public function testResponseErrorForbidden()
    {
        $r = new Handler\Response\ErrorForbidden('forbidden');
        $this->assertTrue($r->isError());
        $this->assertFalse($r->isOk());
        $this->assertFalse($r->isRedirect());
        $this->assertEquals(403, $r->code());
        $this->assertEquals('forbidden', $r->data());
    }

    public function testResponseErrorInternal()
    {
        $r = new Handler\Response\ErrorInternal('internal');
        $this->assertTrue($r->isError());
        $this->assertFalse($r->isOk());
        $this->assertFalse($r->isRedirect());
        $this->assertEquals(500, $r->code());
        $this->assertEquals('internal', $r->data());
    }

    public function testResponseErrorInvalidMethod()
    {
        $r = new Handler\Response\ErrorInvalidMethod('invalid method');
        $this->assertTrue($r->isError());
        $this->assertFalse($r->isOk());
        $this->assertFalse($r->isRedirect());
        $this->assertEquals(405, $r->code());
        $this->assertEquals('invalid method', $r->data());
    }

    public function testResponseErrorInvalidParams()
    {
        $r = new Handler\Response\ErrorInvalidParams('invalid params');
        $this->assertTrue($r->isError());
        $this->assertFalse($r->isOk());
        $this->assertFalse($r->isRedirect());
        $this->assertEquals(400, $r->code());
        $this->assertEquals('invalid params', $r->data());
    }

    public function testResponseErrorNotFound()
    {
        $r = new Handler\Response\ErrorNotFound('not found');
        $this->assertTrue($r->isError());
        $this->assertFalse($r->isOk());
        $this->assertFalse($r->isRedirect());
        $this->assertEquals(404, $r->code());
        $this->assertEquals('not found', $r->data());
    }
}
