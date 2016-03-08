<?php

namespace YenTest\Util;

use Yen\Util\PluginRegistry;
use Yen\Util\Contract\IClassResolver;

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
