<?php

namespace YenTest\Presenter;

use Yen\Presenter\DataPresenter;
use Yen\Renderer\Contract\IDataRenderer;
use Yen\Http\Contract\IResponse;

class DataPresenterTest extends \PHPUnit_Framework_TestCase
{
    public function testPresent()
    {
        $renderer = $this->mockRenderer();

        $presenter = new DataPresenter($renderer);
        $response = $presenter->present('foo');

        $this->assertInstanceOf(IResponse::class, $response);
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals(['Content-Type' => 'test/foo'], $response->getHeaders());
        $this->assertEquals('Foo => Bar', $response->getBody());
    }

    public function testErrorInternal()
    {
        $renderer = $this->mockRenderer();

        $presenter = new DataPresenter($renderer);
        $response = $presenter->errorInternal('foo');

        $this->assertInstanceOf(IResponse::class, $response);
        $this->assertEquals(500, $response->getStatusCode());
        $this->assertEquals(['Content-Type' => 'test/foo'], $response->getHeaders());
        $this->assertEquals('Foo => Bar', $response->getBody());
    }

    public function testErrorNotFound()
    {
        $renderer = $this->mockRenderer();

        $presenter = new DataPresenter($renderer);
        $response = $presenter->errorNotFound('foo');

        $this->assertInstanceOf(IResponse::class, $response);
        $this->assertEquals(404, $response->getStatusCode());
        $this->assertEquals(['Content-Type' => 'test/foo'], $response->getHeaders());
        $this->assertEquals('Foo => Bar', $response->getBody());
    }

    public function testErrorForbidden()
    {
        $renderer = $this->mockRenderer();

        $presenter = new DataPresenter($renderer);
        $response = $presenter->errorForbidden('foo');

        $this->assertInstanceOf(IResponse::class, $response);
        $this->assertEquals(403, $response->getStatusCode());
        $this->assertEquals(['Content-Type' => 'test/foo'], $response->getHeaders());
        $this->assertEquals('Foo => Bar', $response->getBody());
    }

    public function testErrorInvalidParams()
    {
        $renderer = $this->mockRenderer();

        $presenter = new DataPresenter($renderer);
        $response = $presenter->errorInvalidParams('foo');

        $this->assertInstanceOf(IResponse::class, $response);
        $this->assertEquals(400, $response->getStatusCode());
        $this->assertEquals(['Content-Type' => 'test/foo'], $response->getHeaders());
        $this->assertEquals('Foo => Bar', $response->getBody());
    }

    public function testErrorInvalidMethod()
    {
        $renderer = $this->mockRenderer();

        $presenter = new DataPresenter($renderer);
        $response = $presenter->errorInvalidMethod('foo');

        $this->assertInstanceOf(IResponse::class, $response);
        $this->assertEquals(405, $response->getStatusCode());
        $this->assertEquals(['Content-Type' => 'test/foo'], $response->getHeaders());
        $this->assertEquals('Foo => Bar', $response->getBody());
    }

    protected function mockRenderer()
    {
        $renderer = $this->prophesize(IDataRenderer::class);
        $renderer->mime()
                 ->willReturn('test/foo');
        $renderer->render('foo')
                 ->willReturn('Foo => Bar');

        return $renderer->reveal();
    }
}
