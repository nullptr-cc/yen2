<?php

namespace YenTest;

use Yen\Router\Router;
use Yen\Router\Rule;
use Yen\Router\Contract\IRoute;
use Yen\Router\Exception\RouteNotFound;
use Yen\Router\Exception\RuleSyntaxError;

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

    public function testDuplicateRuleException()
    {
        $this->expectException(\LogicException::class);
        $this->expectExceptionMessage('Rule with name "foo" already added');

        $router = new Router();
        $router->addRule('foo', new Rule('/foo', '/x/foo'));
        $router->addRule('foo', new Rule('/foo', '/y/bar'));
    }

    public function testRoute()
    {
        $router = Router::createDefault();

        $route = $router->route('/test');
        $this->assertInstanceOf(IRoute::class, $route);
        $this->assertEquals('test', $route->entry());
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
        $router = new Router();
        $router->addRule('test', new Rule('/test/:foo', 'test/info'));

        $resolved = $router->resolve('test', ['foo' => 'bar']);
        $this->assertInstanceOf('stdClass', $resolved);
        $this->assertObjectHasAttribute('uri', $resolved);
        $this->assertObjectHasAttribute('args', $resolved);
        $this->assertEquals('/test/bar', $resolved->uri);
        $this->assertEquals([], $resolved->args);
    }

    public function testNoResolve()
    {
        $this->expectException(\LogicException::class);
        $this->expectExceptionMessage('Unknown rule "test"');

        $router = new Router();

        $router->resolve('test', ['foo' => 'bar']);
    }

    public function testCreateFromRulesFile()
    {
        $rules = [
            '/test/:foo => test/info',
            '@fzz /fzz => test/fzz',
            '/* => $uri'
        ];

        $vfs = new \MicroVFS\Container();
        $vfs->set('router.rules', implode("\n", $rules));
        \MicroVFS\StreamWrapper::register('mvfs', $vfs);

        $router = Router::createFromRulesFile('mvfs://router.rules');
        $this->assertInstanceOf(Router::class, $router);

        $route = $router->route('/foo');
        $this->assertEquals('foo', $route->entry());
        $this->assertEquals([], $route->arguments());

        $route = $router->route('/test/bar');
        $this->assertEquals('test/info', $route->entry());
        $this->assertEquals(['foo' => 'bar'], $route->arguments());

        $route = $router->route('/fzz');
        $this->assertEquals('test/fzz', $route->entry());
        $this->assertEquals([], $route->arguments());
    }

    public function testCreateFromFileSyntaxError()
    {
        $this->expectException(RuleSyntaxError::class);
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

        $router = Router::createFromRulesFile('mvfs://router.rules');
    }

    public function testCannotCreateFromFile()
    {
        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('Cannot open stream: mvfs://router.rules');

        \MicroVFS\StreamWrapper::register('mvfs', new \MicroVFS\Container());
        $router = Router::createFromRulesFile('mvfs://router.rules');
    }
}
