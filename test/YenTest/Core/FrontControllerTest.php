<?php

namespace YenTest\Core;

use Yen\Core\FrontController;
use Yen\Router\Contract\IRouter;
use Yen\Router\Route;
use Yen\Handler\Contract\IHandlerRegistry;
use Yen\Http\Contract\IResponse;
use Yen\Http\Contract\IRequest;
use Yen\Http\ServerRequest;
use YenMock\Handler\CustomHandler;

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

    public function testProcessRequestErrorNotFound()
    {
        $mocks = $this->prepare();
        $mocks->hregistry
              ->hasHandler('custom')
              ->willReturn(false);

        $fc = new FrontController($mocks->router->reveal(), $mocks->hregistry->reveal());
        $response = $fc->processRequest($mocks->request);

        $this->assertInstanceOf(IResponse::class, $response);
        $this->assertEquals(404, $response->getStatusCode());
        $this->assertEmpty($response->getHeaders());
        $this->assertEquals('', $response->getBody());
    }

    public function testProcessRequestErrorMethodNotAllowed()
    {
        $mocks = $this->prepare();

        $fc = new FrontController($mocks->router->reveal(), $mocks->hregistry->reveal());
        $response = $fc->processRequest($mocks->request->withMethod(IRequest::METHOD_POST));

        $this->assertInstanceOf(IResponse::class, $response);
        $this->assertEquals(405, $response->getStatusCode());
        $this->assertEmpty($response->getHeaders());
        $this->assertEquals('', $response->getBody());
    }

    protected function prepare()
    {
        $request = new ServerRequest(['REQUEST_URI' => '/test', 'REQUEST_METHOD' => IRequest::METHOD_GET]);
        $route = new Route('custom', []);
        $handler = new CustomHandler();

        $router = $this->prophesize(IRouter::class);
        $router->route('/test')
               ->willReturn($route);

        $hregistry = $this->prophesize(IHandlerRegistry::class);
        $hregistry->hasHandler('custom')
                  ->willReturn(true);
        $hregistry->getHandler('custom')
                  ->willReturn($handler);

        return (object)[
            'router' => $router,
            'hregistry' => $hregistry,
            'request' => $request
        ];
    }
}
