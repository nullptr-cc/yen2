<?php

namespace YenTest\Core;

use Yen\Core\MapRegistry;

class MapRegistryTest extends \PHPUnit_Framework_TestCase
{
    public function testHas()
    {
        $dc = new MapRegistry(['foo' => function() { return 'bar'; }]);
        $this->assertTrue($dc->has('foo'));
        $this->assertFalse($dc->has('baz'));
    }

    public function testGet()
    {
        $dc = new MapRegistry(['foo' => function() { return 'bar'; }]);
        $this->assertEquals('bar', $dc->get('foo'));
    }

    /**
     * @expectedException InvalidArgumentException
     * @expectedExceptionMessage unknown name "foo"
     */
    public function testUndefined()
    {
        $dc = new MapRegistry([]);
        $dc->get('foo');
    }
}
