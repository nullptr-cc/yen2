<?php

namespace YenTest\Core;

use Yen\Core\DC;

class DCTest extends \PHPUnit_Framework_TestCase
{
    public function testHas()
    {
        $dc = new DC(['foo' => function() { return 'bar'; }]);
        $this->assertTrue($dc->has('foo'));
        $this->assertFalse($dc->has('baz'));
    }

    public function testGet()
    {
        $dc = new DC(['foo' => function() { return 'bar'; }]);
        $this->assertEquals('bar', $dc->get('foo'));
    }

    /**
     * @expectedException InvalidArgumentException
     * @expectedExceptionMessage DC error: unknown name "foo"
     */
    public function testUndefined()
    {
        $dc = new DC();
        $dc->get('foo');
    }
}
