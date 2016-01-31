<?php

namespace YenTest\Util;

use Yen\Util\MapRegistry;
use YenMock\ArrayContainer;

class MapRegistryTest extends \PHPUnit_Framework_TestCase
{
    public function testHas()
    {
        $mr = new MapRegistry(new ArrayContainer([
            'foo' => function () {
                return 'bar';
            }
        ]));

        $this->assertTrue($mr->has('foo'));
        $this->assertFalse($mr->has('baz'));
    }

    public function testGet()
    {
        $mr = new MapRegistry(new ArrayContainer([
            'foo' => function () {
                return 'bar';
            }
        ]));

        $this->assertEquals('bar', $mr->get('foo'));
    }

    /**
     * @expectedException InvalidArgumentException
     * @expectedExceptionMessage unknown name "foo"
     */
    public function testUndefined()
    {
        $mr = new MapRegistry(new ArrayContainer([]));
        $mr->get('foo');
    }
}
