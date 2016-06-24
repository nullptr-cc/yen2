<?php

namespace YenTest\Core;

use Yen\Core\FrontController;
use Yen\Router\Contract\IRouter;
use Yen\Router\Exception\RouteNotFound;
use Yen\Router\RoutePoint;
use Yen\Handler\Contract\IHandler;
use Yen\Handler\Contract\IHandlerRegistry;
use Yen\Handler\Exception\HandlerNotFound;
use Yen\Http\Contract\IResponse;
use Yen\Http\Contract\IRequest;
use Yen\Http\Response;
use Yen\Http\ServerRequest;

class FrontControllerTest extends \PHPUnit_Framework_TestCase
{
    public function testProcessRequestHappyPath()
    {
        $mocks = $this->prepare();

        $fc = new FrontController($mocks->router->reveal(), $mocks->hregistry->reveal());
        $response = $fc->processRequest($mocks->request);

        $this->assertInstanceOf(IResponse::class, $response);
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEmpty($response->getHeaders());
        $this->assertEquals('ok', $response->getBody());
    }

    public function testProcessRequestErrorRouteNotFound()
    {
        $mocks = $this->prepare();
        $mocks->router
              ->route('/test')
              ->willThrow(new RouteNotFound('/test'));

        $fc = new FrontController($mocks->router->reveal(), $mocks->hregistry->reveal());
        $response = $fc->processRequest($mocks->request);

        $this->assertInstanceOf(IResponse::class, $response);
        $this->assertEquals(404, $response->getStatusCode());
        $this->assertEmpty($response->getHeaders());
        $this->assertEquals('', $response->getBody());
    }

    public function testProcessRequestErrorHandlerNotFound()
    {
        $mocks = $this->prepare();
        $mocks->hregistry
              ->getHandler('custom')
              ->willThrow(new HandlerNotFound());

        $fc = new FrontController($mocks->router->reveal(), $mocks->hregistry->reveal());
        $response = $fc->processRequest($mocks->request);

        $this->assertInstanceOf(IResponse::class, $response);
        $this->assertEquals(404, $response->getStatusCode());
        $this->assertEmpty($response->getHeaders());
        $this->assertEquals('', $response->getBody());
    }

    private function prepare()
    {
        $request = ServerRequest::createFromGlobals(
            ['REQUEST_URI' => '/test', 'REQUEST_METHOD' => IRequest::METHOD_GET]
        );
        $route = new RoutePoint('custom', []);

        $handler = $this->prophesize(IHandler::class);
        $handler->handle($request)
                ->willReturn(Response::ok()->withBody('ok'));

        $nf_handler = $this->prophesize(IHandler::class);
        $nf_handler->handle($request)
                   ->willReturn(Response::notFound());

        $router = $this->prophesize(IRouter::class);
        $router->route('/test')
               ->willReturn($route);

        $hregistry = $this->prophesize(IHandlerRegistry::class);
        $hregistry->getHandler('custom')
                  ->willReturn($handler->reveal());
        $hregistry->getNotFoundHandler()
                  ->willReturn($nf_handler->reveal());

        return (object)[
            'router' => $router,
            'hregistry' => $hregistry,
            'request' => $request
        ];
    }
}
