<?php

namespace YenTest\Core;

use Yen\Core;
use Yen\Http\Uri;
use Yen\Http\Response;
use Yen\Http\Contract\IServerRequest;
use YenMock\Handler\CustomHandler;

class FrontControllerTest extends \PHPUnit_Framework_TestCase
{
    use \YenMock\MockRoute;
    use \YenMock\MockRouter;
    use \YenMock\MockServerRequest;
    use \YenMock\MockHttpResponse;
    use \YenMock\MockHandlerRegistry;

    public function testProcessRequest()
    {
        $response = $this->mockHttpResponse(200);
        $mocks = $this->prepare($response);

        $fc = new Core\FrontController($mocks->router, $mocks->handler_registry);
        $resp = $fc->processRequest($mocks->request);

        $this->assertSame($response, $resp);
    }

    protected function prepare($response)
    {
        $uri = $this->getMockBuilder(Uri::class)
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

        $handler = $this->getMockBuilder(CustomHandler::class)
                        ->disableOriginalConstructor()
                        ->getMock();
        $handler->method('handle')
                ->with($this->identicalTo($request))
                ->willReturn($response);

        $hregistry = $this->mockHandlerRegistry();
        $hregistry->expects($this->once())
                  ->method('getHandler')
                  ->with($this->equalTo('test'))
                  ->willReturn($handler);

        return (object)[
            'router' => $router,
            'handler_registry' => $hregistry,
            'request' => $request
        ];
    }
}
