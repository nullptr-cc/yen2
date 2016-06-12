<?php

namespace YenTest\ClassResolver;

use Yen\ClassResolver\Contract\IClassResolver;
use Yen\ClassResolver\FallbackClassResolver;
use Yen\ClassResolver\ClassNotResolved;

class FallbackClassResolverTest extends \PHPUnit_Framework_TestCase
{
    public function testResolveCommon()
    {
        $resolver = $this->prophesize(IClassResolver::class);
        $resolver->resolve('foo')
                 ->willReturn('ArrayObject');

        $fallback = new FallbackClassResolver($resolver->reveal(), 'ArrayIterator');
        $classname = $fallback->resolve('foo');

        $this->assertEquals('ArrayObject', $classname);
    }

    public function testResolveFallback()
    {
        $resolver = $this->prophesize(IClassResolver::class);
        $resolver->resolve('foo')
                 ->willThrow(new ClassNotResolved('foo'));

        $fallback = new FallbackClassResolver($resolver->reveal(), 'ArrayIterator');
        $classname = $fallback->resolve('foo');

        $this->assertEquals('ArrayIterator', $classname);
    }

    public function testCreateException()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Class not found: FooBar');

        $resolver = $this->prophesize(IClassResolver::class);

        $fallback = new FallbackClassResolver($resolver->reveal(), 'FooBar');
    }
}
