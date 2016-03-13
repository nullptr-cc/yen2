<?php

namespace YenTest\Presenter;

use Yen\Presenter\TemplatePresenter;
use Yen\Renderer\Contract\ITemplateRenderer;
use Yen\Http\Contract\IResponse;

class TemplatePresenterTest extends \PHPUnit_Framework_TestCase
{
    public function testPresent()
    {
        $renderer = $this->mockRenderer();

        $presenter = new TemplatePresenter($renderer);
        $response = $presenter->present('foo', ['foo' => 'bar']);

        $this->assertInstanceOf(IResponse::class, $response);
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals(['Content-Type' => 'test/foo'], $response->getHeaders());
        $this->assertEquals('foo:bar', $response->getBody());
    }

    public function testErrorInternal()
    {
        $renderer = $this->mockRenderer();

        $presenter = new TemplatePresenter($renderer);
        $response = $presenter->errorInternal();

        $this->assertInstanceOf(IResponse::class, $response);
        $this->assertEquals(500, $response->getStatusCode());
        $this->assertEquals(['Content-Type' => 'test/foo'], $response->getHeaders());
        $this->assertEquals('', $response->getBody());
    }

    public function testErrorNotFound()
    {
        $renderer = $this->mockRenderer();

        $presenter = new TemplatePresenter($renderer);
        $response = $presenter->errorNotFound();

        $this->assertInstanceOf(IResponse::class, $response);
        $this->assertEquals(404, $response->getStatusCode());
        $this->assertEquals(['Content-Type' => 'test/foo'], $response->getHeaders());
        $this->assertEquals('', $response->getBody());
    }

    public function testErrorForbidden()
    {
        $renderer = $this->mockRenderer();

        $presenter = new TemplatePresenter($renderer);
        $response = $presenter->errorForbidden();

        $this->assertInstanceOf(IResponse::class, $response);
        $this->assertEquals(403, $response->getStatusCode());
        $this->assertEquals(['Content-Type' => 'test/foo'], $response->getHeaders());
        $this->assertEquals('', $response->getBody());
    }

    public function testErrorInvalidParams()
    {
        $renderer = $this->mockRenderer();

        $presenter = new TemplatePresenter($renderer);
        $response = $presenter->errorInvalidParams();

        $this->assertInstanceOf(IResponse::class, $response);
        $this->assertEquals(400, $response->getStatusCode());
        $this->assertEquals(['Content-Type' => 'test/foo'], $response->getHeaders());
        $this->assertEquals('', $response->getBody());
    }

    public function testErrorInvalidMethod()
    {
        $renderer = $this->mockRenderer();

        $presenter = new TemplatePresenter($renderer);
        $response = $presenter->errorInvalidMethod();

        $this->assertInstanceOf(IResponse::class, $response);
        $this->assertEquals(405, $response->getStatusCode());
        $this->assertEquals(['Content-Type' => 'test/foo'], $response->getHeaders());
        $this->assertEquals('', $response->getBody());
    }

    protected function mockRenderer()
    {
        $renderer = $this->prophesize(ITemplateRenderer::class);
        $renderer->mime()
                 ->willReturn('test/foo');
        $renderer->render('foo', ['foo' => 'bar'])
                 ->willReturn('foo:bar');

        return $renderer->reveal();
    }
}
