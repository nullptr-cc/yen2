<?php

namespace YenTest\Handler;

use Yen\ClassResolver\Contract\IClassResolver;
use Yen\ClassResolver\Exception\ClassNotResolved;
use Yen\Handler\Exception\HandlerNotMaked;
use Yen\Handler\HandlerFactory;
use YenMock\Handler\CustomHandler;

class HandlerFactoryTest extends \PHPUnit_Framework_TestCase
{
    public function testMakeHandler()
    {
        $resolver = $this->prophesize(IClassResolver::class);
        $resolver->resolve('custom')
                 ->willReturn(CustomHandler::class);

        $factory = new HandlerFactory($resolver->reveal());
        $handler = $factory->makeHandler('custom');

        $this->assertInstanceOf(CustomHandler::class, $handler);
    }

    public function testMakeResolved()
    {
        $resolver = $this->prophesize(IClassResolver::class);

        $factory = new HandlerFactory($resolver->reveal());
        $handler = $factory->makeResolved(CustomHandler::class);

        $this->assertInstanceOf(CustomHandler::class, $handler);
    }

    public function testMakeHandlerException()
    {
        $this->expectException(HandlerNotMaked::class);

        $resolver = $this->prophesize(IClassResolver::class);
        $resolver->resolve('fake')
                 ->willThrow(new ClassNotResolved('fake'));

        $factory = new HandlerFactory($resolver->reveal());
        $handler = $factory->makeHandler('fake');
    }
}
