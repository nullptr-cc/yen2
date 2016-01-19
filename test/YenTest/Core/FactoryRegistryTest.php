<?php

namespace YenTest\Core;

use Yen\Core\FactoryRegistry;

class FactoryRegistryTest extends \PHPUnit_Framework_TestCase
{
    public function testHas()
    {
        $reg = new FactoryRegistry(new \YenMock\DummyFactory());
        $this->assertTrue($reg->has('yes'));
        $this->assertFalse($reg->has('no'));
    }

    public function testGet()
    {
        $reg = new FactoryRegistry(new \YenMock\DummyFactory());
        $this->assertEquals('foo', $reg->get('foo'));
    }

    /**
     * @expectedException InvalidArgumentException
     * @expectedExceptionMessage unknown name "no"
     */
    public function testUndefined()
    {
        $reg = new FactoryRegistry(new \YenMock\DummyFactory());
        $reg->get('no');
    }
}
