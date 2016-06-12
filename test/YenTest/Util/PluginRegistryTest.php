<?php

namespace YenTest\Util;

use Yen\ClassResolver\Contract\IClassResolver;
use Yen\Util\PluginRegistry;

class PluginRegistryTest extends \PHPUnit_Framework_TestCase
{
    public function testGetPlugin()
    {
        $resolver = $this->prophesize(IClassResolver::class);
        $resolver->resolve('foo')
                 ->willReturn(\ArrayObject::class);

        $registry = new PluginRegistry($resolver->reveal());
        $plugin = $registry->getPlugin('foo');

        $this->assertInstanceOf(\ArrayObject::class, $plugin);
    }
}
