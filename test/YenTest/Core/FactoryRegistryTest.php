<?php

namespace YenTest\Core;

use Yen\Core\FactoryRegistry;

class DummyFactory implements \Yen\Core\Contract\IFactory
{
    public function make($name)
    {
        return $name;
    }

    public function canMake($name)
    {
        return strlen($name) > 2;
    }
}

class FactoryRegistryTest extends \PHPUnit_Framework_TestCase
{
    public function testHas()
    {
        $reg = new FactoryRegistry(new DummyFactory());
        $this->assertTrue($reg->has('yes'));
        $this->assertFalse($reg->has('no'));
    }

    public function testGet()
    {
        $reg = new FactoryRegistry(new DummyFactory());
        $this->assertEquals('foo', $reg->get('foo'));
    }

    /**
     * @expectedException InvalidArgumentException
     * @expectedExceptionMessage unknown name "no"
     */
    public function testUndefined()
    {
        $reg = new FactoryRegistry(new DummyFactory());
        $reg->get('no');
    }
}
