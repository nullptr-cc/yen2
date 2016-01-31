<?php

namespace YenTest;

use Yen\View\View;
use Yen\View\DefaultView;

class ViewTest extends \PHPUnit_Framework_TestCase
{
    public function testHandleOk()
    {
        $response = new \Yen\Handler\Response\Ok();

        $view = new DefaultView();
        $vr = $view->present('GET', $response);
        $this->assertInstanceOf('\Yen\Http\Response', $vr);
        $this->assertEquals(200, $vr->getStatusCode());
        $this->assertEquals(['Content-Type' => 'text/plain'], $vr->getHeaders());
        $this->assertEquals('', $vr->getBody());
    }

    public function testHandleError()
    {
        $response = new \Yen\Handler\Response\ErrorNotFound('error message');

        $view = new DefaultView();
        $vr = $view->present('GET', $response);
        $this->assertInstanceOf('\Yen\Http\Response', $vr);
        $this->assertEquals(404, $vr->getStatusCode());
        $this->assertEquals(['Content-Type' => 'text/plain'], $vr->getHeaders());
        $this->assertEquals('error message', $vr->getBody());
    }

    public function testHandleRedirect()
    {
        $response = new \Yen\Handler\Response\Redirect('http://test.net');

        $view = new DefaultView();
        $vr = $view->present('GET', $response);
        $this->assertInstanceOf('\Yen\Http\Response', $vr);
        $this->assertEquals(302, $vr->getStatusCode());
        $this->assertEquals(['Location' => 'http://test.net'], $vr->getHeaders());
        $this->assertNull($vr->getBody());
    }

    public function testHandleGetOk()
    {
        $response = new \Yen\Handler\Response\Ok();

        $view = new \YenMock\View\CustomView();
        $vr = $view->present('GET', $response);
        $this->assertInstanceOf('\Yen\Http\Response', $vr);
        $this->assertEquals(200, $vr->getStatusCode());
        $this->assertEquals(['Content-Type' => 'text/plain'], $vr->getHeaders());
        $this->assertEquals('get ok', $vr->getBody());
    }

    public function testHandleGetError()
    {
        $response = new \Yen\Handler\Response\ErrorNotFound('message');

        $view = new \YenMock\View\CustomView();
        $vr = $view->present('GET', $response);
        $this->assertInstanceOf('\Yen\Http\Response', $vr);
        $this->assertEquals(404, $vr->getStatusCode());
        $this->assertEquals(['Content-Type' => 'text/plain'], $vr->getHeaders());
        $this->assertEquals('get error: message', $vr->getBody());
    }

    public function testHandleGetExactError()
    {
        $response = new \Yen\Handler\Response\ErrorNotFound('message');

        $view = new \YenMock\View\NotFoundView();
        $vr = $view->present('GET', $response);
        $this->assertInstanceOf('\Yen\Http\Response', $vr);
        $this->assertEquals(404, $vr->getStatusCode());
        $this->assertEquals(['Content-Type' => 'text/plain'], $vr->getHeaders());
        $this->assertEquals('get error not found: message', $vr->getBody());
    }

    public function testMissedMethod()
    {
        $response = new \Yen\Handler\Response\Ok();

        $view = new \YenMock\View\MissedMethodView();
        $vr = $view->present('GET', $response);
        $this->assertInstanceOf('\Yen\Http\Response', $vr);
        $this->assertEquals(200, $vr->getStatusCode());
        $this->assertEquals(['Content-Type' => 'text/plain'], $vr->getHeaders());
        $this->assertEquals('You have missed view method for: GET, Ok', $vr->getBody());
    }
}
