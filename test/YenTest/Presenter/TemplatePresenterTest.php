<?php

namespace YenTest\Presenter;

use Yen\Presenter\TemplatePresenter;
use Yen\Presenter\Contract\IComponentRegistry;
use Yen\Renderer\Contract\ITemplateRenderer;
use Yen\Http\Contract\IResponse;

class TemplatePresenterTest extends \PHPUnit_Framework_TestCase
{
    public function testPresent()
    {
        $renderer = $this->mockRenderer();
        $components = $this->mockComponents();

        $presenter = new TemplatePresenter($renderer, $components);
        $response = $presenter->present('Foo', ['foo', 'bar']);

        $this->assertInstanceOf(IResponse::class, $response);
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals(['Content-Type' => 'test/foo'], $response->getHeaders());
        $this->assertEquals('foobar', $response->getBody());
    }

    public function testErrorInternal()
    {
        $renderer = $this->mockRenderer();
        $components = $this->mockComponents();

        $presenter = new TemplatePresenter($renderer, $components);
        $response = $presenter->errorInternal();

        $this->assertInstanceOf(IResponse::class, $response);
        $this->assertEquals(500, $response->getStatusCode());
        $this->assertEquals(['Content-Type' => 'test/foo'], $response->getHeaders());
        $this->assertEquals('', $response->getBody());
    }

    public function testErrorNotFound()
    {
        $renderer = $this->mockRenderer();
        $components = $this->mockComponents();

        $presenter = new TemplatePresenter($renderer, $components);
        $response = $presenter->errorNotFound();

        $this->assertInstanceOf(IResponse::class, $response);
        $this->assertEquals(404, $response->getStatusCode());
        $this->assertEquals(['Content-Type' => 'test/foo'], $response->getHeaders());
        $this->assertEquals('', $response->getBody());
    }

    public function testErrorForbidden()
    {
        $renderer = $this->mockRenderer();
        $components = $this->mockComponents();

        $presenter = new TemplatePresenter($renderer, $components);
        $response = $presenter->errorForbidden();

        $this->assertInstanceOf(IResponse::class, $response);
        $this->assertEquals(403, $response->getStatusCode());
        $this->assertEquals(['Content-Type' => 'test/foo'], $response->getHeaders());
        $this->assertEquals('', $response->getBody());
    }

    public function testErrorInvalidParams()
    {
        $renderer = $this->mockRenderer();
        $components = $this->mockComponents();

        $presenter = new TemplatePresenter($renderer, $components);
        $response = $presenter->errorInvalidParams();

        $this->assertInstanceOf(IResponse::class, $response);
        $this->assertEquals(400, $response->getStatusCode());
        $this->assertEquals(['Content-Type' => 'test/foo'], $response->getHeaders());
        $this->assertEquals('', $response->getBody());
    }

    public function testErrorInvalidMethod()
    {
        $renderer = $this->mockRenderer();
        $components = $this->mockComponents();

        $presenter = new TemplatePresenter($renderer, $components);
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

        return $renderer->reveal();
    }

    protected function mockComponents()
    {
        $components = $this->prophesize(IComponentRegistry::class);
        $components->getComponent('Foo')
                   ->willReturn('implode');

        return $components->reveal();
    }
}
