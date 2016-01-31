<?php

namespace YenTest\Core;

use Yen\Core\DependencyContainer;

class DependencyContainerTest extends \PHPUnit_Framework_TestCase
{
    public function testGetRouter()
    {
        $dc = new DependencyContainer();
        $router = $dc->getRouter();

        $this->assertInstanceOf(\Yen\Router\Contract\IRouter::class, $router);
    }

    public function testGetHandlerRegistry()
    {
        $dc = new DependencyContainer();
        $handler_registry = $dc->getHandlerRegistry();

        $this->assertInstanceOf(\Yen\Util\FactoryRegistry::class, $handler_registry);
    }

    public function testGetViewRegistry()
    {
        $dc = new DependencyContainer();
        $view_registry = $dc->getViewRegistry();

        $this->assertInstanceOf(\Yen\Util\FactoryRegistry::class, $view_registry);
    }
}
