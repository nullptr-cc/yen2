<?php

namespace YenTest\Core;

use Yen\Core;

class FrontControllerTest extends \PHPUnit_Framework_TestCase
{
    use \YenMock\MockDC;
    use \YenMock\MockRoute;
    use \YenMock\MockRouter;
    use \YenMock\MockServerRequest;
    use \YenMock\MockHttpResponse;
    use \YenMock\MockRegistry;

    public function testProcessRequest()
    {
        list($dc, $request) = $this->prepare();

        $fc = new Core\FrontController($dc);
        $resp = $fc->processRequest($request);

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

        $request = $this->mockServerRequest();
        $request->method('getMethod')
                ->willReturn('GET');
        $request->method('getUri')
                ->willReturn($uri);

        $router = $this->mockRouter();
        $router->method('route')
               ->willReturn($this->mockRoute('test'));

        $handler = $this->getMockBuilder('\YenMock\Handler\CustomHandler')
                        ->disableOriginalConstructor()
                        ->getMock();
        $handler->method('handle')
                ->with(
                    $this->equalTo('GET'),
                    $this->isInstanceOf('\Yen\Handler\Request')
                )->willReturn(
                    new \Yen\Handler\Response\Ok()
                );

        $hregistry = $this->mockRegistry();
        $hregistry->expects($this->once())
                  ->method('get')
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

        $vregistry = $this->mockRegistry();
        $vregistry->expects($this->once())
                  ->method('get')
                  ->with($this->equalTo('test'))
                  ->willReturn($view);

        $dc = $this->mockDC([
            'router' => $router,
            'handler_registry' => $hregistry,
            'view_registry' => $vregistry
        ]);

        return [$dc, $request];
    }
}
