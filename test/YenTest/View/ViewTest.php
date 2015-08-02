<?php

namespace YenTest;

use Yen\View\View;
use Yen\View\NullView;

class CustomView extends View
{
    protected function onGetOk($data)
    {
        $headers = ['Content-Type' => 'text/plain'];
        $body = 'get ok';
        return [$headers, $body];
    }

    protected function onGetError($data)
    {
        $headers = ['Content-Type' => 'text/plain'];
        $body = 'get error: ' . $data;
        return [$headers, $body];
    }
}

class NotFoundView extends View
{
    protected function onGetErrorNotFound($data)
    {
        $headers = ['Content-Type' => 'text/plain'];
        $body = 'get error not found: ' . $data;
        return [$headers, $body];
    }
}

class ViewTest extends \PHPUnit_Framework_TestCase
{
    public function testHandleOk()
    {
        $dc = new \Yen\Core\DC();
        $response = new \Yen\Handler\Response\Ok();

        $view = new NullView($dc);
        $vr = $view->handle('GET', $response);
        $this->assertInstanceOf('\Yen\Http\Response', $vr);
        $this->assertEquals(200, $vr->getStatusCode());
        $this->assertEquals(['Content-Type' => 'text/plain'], $vr->getHeaders());
        $this->assertEquals('You have missed view method', $vr->getBody());
    }

    public function testHandleError()
    {
        $dc = new \Yen\Core\DC();
        $response = new \Yen\Handler\Response\ErrorNotFound('error message');

        $view = new NullView($dc);
        $vr = $view->handle('GET', $response);
        $this->assertInstanceOf('\Yen\Http\Response', $vr);
        $this->assertEquals(404, $vr->getStatusCode());
        $this->assertEquals(['Content-Type' => 'text/plain'], $vr->getHeaders());
        $this->assertEquals('error message', $vr->getBody());
    }

    public function testHandleRedirect()
    {
        $dc = new \Yen\Core\DC();
        $response = new \Yen\Handler\Response\Redirect('http://test.net');

        $view = new NullView($dc);
        $vr = $view->handle('GET', $response);
        $this->assertInstanceOf('\Yen\Http\Response', $vr);
        $this->assertEquals(302, $vr->getStatusCode());
        $this->assertEquals(['Location' => 'http://test.net'], $vr->getHeaders());
        $this->assertNull($vr->getBody());
    }

    public function testHandleGetOk()
    {
        $dc = new \Yen\Core\DC();
        $response = new \Yen\Handler\Response\Ok();

        $view = new CustomView($dc);
        $vr = $view->handle('GET', $response);
        $this->assertInstanceOf('\Yen\Http\Response', $vr);
        $this->assertEquals(200, $vr->getStatusCode());
        $this->assertEquals(['Content-Type' => 'text/plain'], $vr->getHeaders());
        $this->assertEquals('get ok', $vr->getBody());
    }

    public function testHandleGetError()
    {
        $dc = new \Yen\Core\DC();
        $response = new \Yen\Handler\Response\ErrorNotFound('message');

        $view = new CustomView($dc);
        $vr = $view->handle('GET', $response);
        $this->assertInstanceOf('\Yen\Http\Response', $vr);
        $this->assertEquals(404, $vr->getStatusCode());
        $this->assertEquals(['Content-Type' => 'text/plain'], $vr->getHeaders());
        $this->assertEquals('get error: message', $vr->getBody());
    }

    public function testHandleGetExactError()
    {
        $dc = new \Yen\Core\DC();
        $response = new \Yen\Handler\Response\ErrorNotFound('message');

        $view = new NotFoundView($dc);
        $vr = $view->handle('GET', $response);
        $this->assertInstanceOf('\Yen\Http\Response', $vr);
        $this->assertEquals(404, $vr->getStatusCode());
        $this->assertEquals(['Content-Type' => 'text/plain'], $vr->getHeaders());
        $this->assertEquals('get error not found: message', $vr->getBody());
    }
}
