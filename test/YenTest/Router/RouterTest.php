<?php

namespace YenTest;

include_once __DIR__ . '/../../MicroVFS.php';

use Yen\Router\Router;
use Yen\Router\Rule;

class RouterTest extends \PHPUnit_Framework_TestCase
{
    public function testCreateDefault()
    {
        $this->assertInstanceOf('\Yen\Router\Router', Router::createDefault());
    }

    public function testRoute()
    {
        $router = Router::createDefault();

        $route = $router->route('/test');
        $this->assertInstanceOf('\Yen\Router\Contract\IRoute', $route);
        $this->assertEquals('test', $route->entry());
        $this->assertEquals([], $route->arguments());
    }

    public function testNoRoute()
    {
        $router = new Router();

        $route = $router->route('/test');
        $this->assertInstanceOf('\Yen\Router\Contract\IRoute', $route);
        $this->assertNull($route->entry());
        $this->assertNull($route->arguments());
    }

    public function testResolve()
    {
        $router = new Router(['test' => new Rule('/test/:foo', 'test/info')]);

        $resolved = $router->resolve('test', ['foo' => 'bar']);
        $this->assertInstanceOf('stdClass', $resolved);
        $this->assertObjectHasAttribute('uri', $resolved);
        $this->assertObjectHasAttribute('args', $resolved);
        $this->assertEquals('/test/bar', $resolved->uri);
        $this->assertEquals([], $resolved->args);
    }

    public function testNoResolve()
    {
        $router = new Router();

        $this->assertNull($router->resolve('test', ['foo' => 'bar']));
    }

    public function testCreateFromRulesFile()
    {
        $rules = [
            '/test/:foo => test/info',
            '@fzz /fzz => test/fzz',
            '/* => $uri',
            'incorrect rule will be skipped'
        ];

        $vfs = new \MicroVFS\Container();
        $vfs->set('router.rules', implode("\n", $rules));
        \MicroVFS\StreamWrapper::register('mvfs', $vfs);

        $router = Router::createFromRulesFile('mvfs://router.rules');
        $this->assertInstanceOf('\Yen\Router\Router', $router);

        $route = $router->route('/foo');
        $this->assertEquals('foo', $route->entry());
        $this->assertEquals([], $route->arguments());

        $route = $router->route('/test/bar');
        $this->assertEquals('test/info', $route->entry());
        $this->assertEquals(['foo' => 'bar'], $route->arguments());

        $route = $router->route('/fzz');
        $this->assertEquals('test/fzz', $route->entry());
        $this->assertEquals([], $route->arguments());

        \MicroVFS\StreamWrapper::unregister('mvfs');
    }

    /**
     * @expectedException InvalidArgumentException
     * @expectedExceptionMessage Cannot open stream: mvfs://router.rules
     */
    public function testCannotCreateFromFile()
    {
        \MicroVFS\StreamWrapper::register('mvfs', new \MicroVFS\Container());
        $router = Router::createFromRulesFile('mvfs://router.rules');
        \MicroVFS\StreamWrapper::unregister('mvfs');
    }
}
