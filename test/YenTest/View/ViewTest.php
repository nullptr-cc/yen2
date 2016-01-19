<?php

namespace YenTest;

use Yen\View\View;
use Yen\View\DefaultView;

class ViewTest extends \PHPUnit_Framework_TestCase
{
    use \YenMock\MockDC;

    public function testHandleOk()
    {
        $dc = $this->mockDC();
        $response = new \Yen\Handler\Response\Ok();

        $view = new DefaultView($dc);
        $vr = $view->present('GET', $response);
        $this->assertInstanceOf('\Yen\Http\Response', $vr);
        $this->assertEquals(200, $vr->getStatusCode());
        $this->assertEquals(['Content-Type' => 'text/plain'], $vr->getHeaders());
        $this->assertEquals('', $vr->getBody());
    }

    public function testHandleError()
    {
        $dc = $this->mockDC();
        $response = new \Yen\Handler\Response\ErrorNotFound('error message');

        $view = new DefaultView($dc);
        $vr = $view->present('GET', $response);
        $this->assertInstanceOf('\Yen\Http\Response', $vr);
        $this->assertEquals(404, $vr->getStatusCode());
        $this->assertEquals(['Content-Type' => 'text/plain'], $vr->getHeaders());
        $this->assertEquals('error message', $vr->getBody());
    }

    public function testHandleRedirect()
    {
        $dc = $this->mockDC();
        $response = new \Yen\Handler\Response\Redirect('http://test.net');

        $view = new DefaultView($dc);
        $vr = $view->present('GET', $response);
        $this->assertInstanceOf('\Yen\Http\Response', $vr);
        $this->assertEquals(302, $vr->getStatusCode());
        $this->assertEquals(['Location' => 'http://test.net'], $vr->getHeaders());
        $this->assertNull($vr->getBody());
    }

    public function testHandleGetOk()
    {
        $dc = $this->mockDC();
        $response = new \Yen\Handler\Response\Ok();

        $view = new \YenMock\View\CustomView($dc);
        $vr = $view->present('GET', $response);
        $this->assertInstanceOf('\Yen\Http\Response', $vr);
        $this->assertEquals(200, $vr->getStatusCode());
        $this->assertEquals(['Content-Type' => 'text/plain'], $vr->getHeaders());
        $this->assertEquals('get ok', $vr->getBody());
    }

    public function testHandleGetError()
    {
        $dc = $this->mockDC();
        $response = new \Yen\Handler\Response\ErrorNotFound('message');

        $view = new \YenMock\View\CustomView($dc);
        $vr = $view->present('GET', $response);
        $this->assertInstanceOf('\Yen\Http\Response', $vr);
        $this->assertEquals(404, $vr->getStatusCode());
        $this->assertEquals(['Content-Type' => 'text/plain'], $vr->getHeaders());
        $this->assertEquals('get error: message', $vr->getBody());
    }

    public function testHandleGetExactError()
    {
        $dc = $this->mockDC();
        $response = new \Yen\Handler\Response\ErrorNotFound('message');

        $view = new \YenMock\View\NotFoundView($dc);
        $vr = $view->present('GET', $response);
        $this->assertInstanceOf('\Yen\Http\Response', $vr);
        $this->assertEquals(404, $vr->getStatusCode());
        $this->assertEquals(['Content-Type' => 'text/plain'], $vr->getHeaders());
        $this->assertEquals('get error not found: message', $vr->getBody());
    }

    public function testMissedMethod()
    {
        $dc = $this->mockDC();
        $response = new \Yen\Handler\Response\Ok();

        $view = new \YenMock\View\MissedMethodView($dc);
        $vr = $view->present('GET', $response);
        $this->assertInstanceOf('\Yen\Http\Response', $vr);
        $this->assertEquals(200, $vr->getStatusCode());
        $this->assertEquals(['Content-Type' => 'text/plain'], $vr->getHeaders());
        $this->assertEquals('You have missed view method for: GET, Ok', $vr->getBody());
    }
}
