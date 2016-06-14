<?php

namespace YenTest\Handler;

use Yen\Handler\Contract\IHandlerFactory;
use Yen\Handler\HandlerRegistry;
use Yen\Handler\Exception\HandlerNotFound;
use Yen\Handler\Exception\HandlerNotMaked;
use YenMock\Handler\CustomHandler;

class HandlerRegistryTest extends \PHPUnit_Framework_TestCase
{
    public function testGetHandler()
    {
        $factory = $this->prophesize(IHandlerFactory::class);
        $factory->makeHandler('custom')
                ->willReturn(new CustomHandler());

        $registry = new HandlerRegistry($factory->reveal());
        $handler = $registry->getHandler('custom');

        $this->assertInstanceOf(CustomHandler::class, $handler);
    }

    public function testGetHandlerException()
    {
        $this->expectException(HandlerNotFound::class);

        $factory = $this->prophesize(IHandlerFactory::class);
        $factory->makeHandler('custom')
                ->willThrow(new HandlerNotMaked());

        $registry = new HandlerRegistry($factory->reveal());
        $handler = $registry->getHandler('custom');
    }

    public function testHasHandler()
    {
        $factory = $this->prophesize(IHandlerFactory::class);
        $factory->makeHandler('custom')
                ->willReturn(new CustomHandler());
        $factory->makeHandler('fake')
                ->willThrow(new HandlerNotMaked());

        $registry = new HandlerRegistry($factory->reveal());

        $this->assertTrue($registry->hasHandler('custom'));
        $this->assertFalse($registry->hasHandler('fake'));
    }

    public function testDoubleCall()
    {
        $factory = $this->prophesize(IHandlerFactory::class);
        $factory->makeHandler('custom')
                ->willReturn(new CustomHandler());

        $registry = new HandlerRegistry($factory->reveal());

        $this->assertTrue($registry->hasHandler('custom'));
        $this->assertInstanceOf(CustomHandler::class, $registry->getHandler('custom'));
    }
}
