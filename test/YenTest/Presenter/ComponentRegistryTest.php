<?php

namespace YenTest\Presenter;

use Yen\Presenter\ComponentRegistry;
use Yen\Util\Contract\IClassResolver;

class ComponentRegistryTest extends \PHPUnit_Framework_TestCase
{
    public function testGetComponent()
    {
        $resolver = $this->prophesize(IClassResolver::class);
        $resolver->resolve('foo')
                 ->willReturn(\ArrayObject::class);

        $registry = new ComponentRegistry($resolver->reveal());
        $component = $registry->getComponent('foo');

        $this->assertInstanceOf(\ArrayObject::class, $component);
    }
}
