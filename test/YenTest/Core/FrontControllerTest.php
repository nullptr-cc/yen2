<?php

namespace YenTest\Core;

use Yen\Core;

class FrontControllerTest extends \PHPUnit_Framework_TestCase
{
    use \YenMock\MockDependencyContainer;
    use \YenMock\MockRoute;
    use \YenMock\MockRouter;
    use \YenMock\MockServerRequest;
    use \YenMock\MockHttpResponse;
    use \YenMock\MockRegistry;
    use \YenMock\MockHandlerRegistry;
    use \YenMock\MockViewRegistry;

    public function testCreateFromDC()
    {
        $router = $this->mockRouter();
        $hregistry = $this->mockHandlerRegistry();
        $vregistry = $this->mockViewRegistry();

        $dc = $this->mockDependencyContainer();
        $dc->method('getRouter')
           ->willReturn($router);
        $dc->method('getHandlerRegistry')
           ->willReturn($hregistry);
        $dc->method('getViewRegistry')
           ->willReturn($vregistry);

        $fc = Core\FrontController::createFromDC($dc);

        $this->assertInstanceOf(Core\FrontController::class, $fc);
    }

    public function testProcessRequest()
    {
        $mocks = $this->prepare();

        $fc = new Core\FrontController($mocks->router, $mocks->handler_registry, $mocks->view_registry);
        $resp = $fc->processRequest($mocks->request);

        $this->assertInstanceOf('\Yen\Http\Response', $resp);
        $this->assertEquals(200, $resp->getStatusCode());
        $this->assertEquals(['Content-Type' => 'text/plain'], $resp->getHeaders());
        $this->assertEquals('ok', $resp->getBody());
    }

    protected function prepare()
    {
        $uri = $this->getMockBuilder('\Yen\Http\Uri')
                    ->disableOriginalConstructor()
                    ->getMock();
        $uri->method('getPath')
            ->willReturn('/test');

        $request = $this->mockServerRequest('GET');
        $request->method('getUri')
                ->willReturn($uri);
        $request->method('withJoinedQueryParams')
                ->will($this->returnSelf());

        $router = $this->mockRouter();
        $router->method('route')
               ->willReturn($this->mockRoute('test'));

        $handler = $this->getMockBuilder('\YenMock\Handler\CustomHandler')
                        ->disableOriginalConstructor()
                        ->getMock();
        $handler->method('handle')
                ->with(
                    $this->isInstanceOf('\Yen\Http\Contract\IServerRequest')
                )->willReturn(
                    new \Yen\Handler\Response\Ok()
                );

        $hregistry = $this->mockHandlerRegistry();
        $hregistry->expects($this->once())
                  ->method('getHandler')
                  ->with($this->equalTo('test'))
                  ->willReturn($handler);

        $view = $this->getMockBuilder('\Yen\View\DefaultView')
                     ->disableOriginalConstructor()
                     ->getMock();
        $view->method('present')
             ->with(
                 $this->equalTo('GET'),
                 $this->isInstanceOf('\Yen\Handler\Response')
             )->willReturn(
                 $this->mockHttpResponse(200, ['Content-Type' => 'text/plain'], 'ok')
             );

        $vregistry = $this->mockViewRegistry();
        $vregistry->expects($this->once())
                  ->method('getView')
                  ->with($this->equalTo('test'))
                  ->willReturn($view);

        return (object)[
            'router' => $router,
            'handler_registry' => $hregistry,
            'view_registry' => $vregistry,
            'request' => $request
        ];
    }
}
