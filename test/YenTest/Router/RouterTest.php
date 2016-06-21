<?php

namespace YenTest;

use Yen\Router\Router;
use Yen\Router\Rule;
use Yen\Router\Contract\IRoutePoint;
use Yen\Router\Exception\RouteNotFound;
use Yen\Router\Exception\RouteSyntaxError;

class RouterTest extends \PHPUnit_Framework_TestCase
{
    public function tearDown()
    {
        \MicroVFS\StreamWrapper::unregister('mvfs');
    }

    public function testCreateDefault()
    {
        $this->assertInstanceOf(Router::class, Router::createDefault());
    }

    public function testRoute()
    {
        $router = Router::createDefault();

        $route = $router->route('/test');
        $this->assertInstanceOf(IRoutePoint::class, $route);
        $this->assertEquals('test', $route->path());
        $this->assertEquals([], $route->arguments());
    }

    public function testNoRoute()
    {
        $this->expectException(RouteNotFound::class);
        $this->expectExceptionMessage('Route for URI "/test" not found');

        $router = new Router();

        $route = $router->route('/test');
    }

    public function testResolve()
    {
        $rules = [
            '@test /test/:foo => test/info'
        ];

        $vfs = new \MicroVFS\Container();
        $vfs->set('router.rules', implode("\n", $rules));
        \MicroVFS\StreamWrapper::register('mvfs', $vfs);

        $router = Router::createFromRoutesFile('mvfs://router.rules');

        $resolved = $router->resolve('test', ['foo' => 'bar']);
        $this->assertInstanceOf(IRoutePoint::class, $resolved);
        $this->assertEquals('/test/bar', $resolved->path());
        $this->assertEquals([], $resolved->arguments());
    }

    public function testNoResolve()
    {
        $this->expectException(\LogicException::class);
        $this->expectExceptionMessage('Unknown route: test');

        $router = new Router();

        $router->resolve('test', ['foo' => 'bar']);
    }

    public function testCreateFromRoutesFile()
    {
        $rules = [
            '/test/:foo => test/info',
            '@fzz /fzz => test/fzz',
            '/* => $uri'
        ];

        $vfs = new \MicroVFS\Container();
        $vfs->set('router.rules', implode("\n", $rules));
        \MicroVFS\StreamWrapper::register('mvfs', $vfs);

        $router = Router::createFromRoutesFile('mvfs://router.rules');
        $this->assertInstanceOf(Router::class, $router);

        $route = $router->route('/foo');
        $this->assertEquals('foo', $route->path());
        $this->assertEquals([], $route->arguments());

        $route = $router->route('/test/bar');
        $this->assertEquals('test/info', $route->path());
        $this->assertEquals(['foo' => 'bar'], $route->arguments());

        $route = $router->route('/fzz');
        $this->assertEquals('test/fzz', $route->path());
        $this->assertEquals([], $route->arguments());
    }

    public function testCreateFromFileSyntaxError()
    {
        $this->expectException(RouteSyntaxError::class);
        $this->expectExceptionMessage('Routing rule syntax error at #3 "rule with syntax error"');

        $rules = [
            '/test/:foo => test/info',
            '@fzz /fzz => test/fzz',
            '/* => $uri',
            'rule with syntax error'
        ];

        $vfs = new \MicroVFS\Container();
        $vfs->set('router.rules', implode("\n", $rules));
        \MicroVFS\StreamWrapper::register('mvfs', $vfs);

        $router = Router::createFromRoutesFile('mvfs://router.rules');
    }

    public function testCannotCreateFromFile()
    {
        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('Cannot open stream: mvfs://router.rules');

        \MicroVFS\StreamWrapper::register('mvfs', new \MicroVFS\Container());
        $router = Router::createFromRoutesFile('mvfs://router.rules');
    }

    public function testDuplicateRouteNameException()
    {
        $this->expectException(\LogicException::class);
        $this->expectExceptionMessage('Route with name "test" already added');

        $rules = [
            '@test /test/:foo => test/info',
            '@fzz /fzz => test/fzz',
            '@test /foobar => /foo/bar',
            '/* => $uri',
        ];

        $vfs = new \MicroVFS\Container();
        $vfs->set('router.rules', implode("\n", $rules));
        \MicroVFS\StreamWrapper::register('mvfs', $vfs);

        $router = Router::createFromRoutesFile('mvfs://router.rules');
    }
}
