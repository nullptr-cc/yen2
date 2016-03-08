<?php

namespace YenTest\Handler;

use Yen\Handler;
use Yen\Http\Response;
use Yen\Http\Uri;
use Yen\Presenter\Contract\IPresenter;
use Yen\Presenter\Contract\IErrorPresenter;
use YenMock\Handler\CustomHandler;
use YenMock\Handler\RevealingHandler;
use YenMock\Handler\RedirectHandler;

class HandlerTest extends \PHPUnit_Framework_TestCase
{
    use \YenMock\MockServerRequest;
    use \YenMock\MockHttpResponse;

    public function testHandle()
    {
        $request = $this->mockServerRequest('GET');
        $handler = new CustomHandler();

        $hr = $handler->handle($request);

        $this->assertInstanceOf(Response::class, $hr);
        $this->assertEquals(200, $hr->getStatusCode());
    }

    public function testHandleInvalidMethod()
    {
        $response = $this->mockHttpResponse(405);

        $presenter = $this->getMockForAbstractClass(IErrorPresenter::class);
        $presenter
            ->expects($this->once())
            ->method('errorInvalidMethod')
            ->willReturn($response);

        $handler = $this->getMockForAbstractClass(Handler\Handler::class);
        $handler->expects($this->once())
                ->method('getErrorPresenter')
                ->willReturn($presenter);

        $request = $this->mockServerRequest('POST');

        $resp = $handler->handle($request);

        $this->assertInstanceOf(Response::class, $resp);
        $this->assertEquals(405, $resp->getStatusCode());
    }

    public function testShortcuts()
    {
        $presenter = $this->prophesize(IPresenter::class);
        $presenter
            ->present('data-ok')
            ->shouldBeCalled()
            ->willReturn('response-ok');

        $error_presenter = $this->prophesize(IErrorPresenter::class);
        $error_presenter
            ->errorInternal('data-internal-error')
            ->shouldBeCalled()
            ->willReturn('response-internal-error');
        $error_presenter
            ->errorInvalidParams('data-invalid-params')
            ->shouldBeCalled()
            ->willReturn('response-invalid-params');
        $error_presenter
            ->errorForbidden('data-forbidden')
            ->shouldBeCalled()
            ->willReturn('response-forbidden');
        $error_presenter
            ->errorNotFound('data-not-found')
            ->shouldBeCalled()
            ->willReturn('response-not-found');

        $handler = new RevealingHandler($presenter->reveal(), $error_presenter->reveal());

        $resp = $handler->ok('data-ok');
        $this->assertEquals('response-ok', $resp);

        $resp = $handler->error('data-internal-error');
        $this->assertEquals('response-internal-error', $resp);

        $resp = $handler->badParams('data-invalid-params');
        $this->assertEquals('response-invalid-params', $resp);

        $resp = $handler->forbidden('data-forbidden');
        $this->assertEquals('response-forbidden', $resp);

        $resp = $handler->notFound('data-not-found');
        $this->assertEquals('response-not-found', $resp);
    }

    public function testRedirect()
    {
        $handler = new RedirectHandler();
        $uri = Uri::createFromString($url = 'https://images.google.com/search?q=sunrise');

        $resp = $handler->redirect($uri);

        $this->assertInstanceOf(Response::class, $resp);
        $this->assertEquals(302, $resp->getStatusCode());
        $this->assertEquals(['Location' => $url], $resp->getHeaders());

        $resp = $handler->redirect($uri, true);

        $this->assertInstanceOf(Response::class, $resp);
        $this->assertEquals(301, $resp->getStatusCode());
        $this->assertEquals(['Location' => $url], $resp->getHeaders());
    }
}
