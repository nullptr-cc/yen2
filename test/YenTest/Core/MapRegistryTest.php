<?php

namespace YenTest\Core;

use Yen\Core\MapRegistry;
use YenMock\ArrayContainer;

class MapRegistryTest extends \PHPUnit_Framework_TestCase
{
    public function testHas()
    {
        $dc = new MapRegistry(new ArrayContainer([
            'foo' => function () {
                return 'bar';
            }
        ]));

        $this->assertTrue($dc->has('foo'));
        $this->assertFalse($dc->has('baz'));
    }

    public function testGet()
    {
        $dc = new MapRegistry(new ArrayContainer([
            'foo' => function () {
                return 'bar';
            }
        ]));

        $this->assertEquals('bar', $dc->get('foo'));
    }

    /**
     * @expectedException InvalidArgumentException
     * @expectedExceptionMessage unknown name "foo"
     */
    public function testUndefined()
    {
        $dc = new MapRegistry(new ArrayContainer([]));
        $dc->get('foo');
    }
}
