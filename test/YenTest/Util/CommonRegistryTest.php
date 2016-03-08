<?php

namespace YenTest\Util;

use Yen\Util\Contract\IClassResolver;
use YenMock\Util\DummyRegistry;

class CommonRegistryTest extends \PHPUnit_Framework_TestCase
{
    public function testGet()
    {
        $resolver = $this->prophesize(IClassResolver::class);
        $resolver->resolve('foo')
                 ->willReturn('ArrayObject');

        $registry = new DummyRegistry($resolver->reveal());
        $array = $registry->get('foo');

        $this->assertInstanceOf('ArrayObject', $array);
    }

    public function testInvalidNameException()
    {
        $this->expectException(\LogicException::class);
        $this->expectExceptionMessage('Unknown');

        $resolver = $this->prophesize(IClassResolver::class);
        $resolver->resolve('foo')
                 ->willReturn(false);

        $registry = new DummyRegistry($resolver->reveal());
        $registry->get('foo');
    }
}
