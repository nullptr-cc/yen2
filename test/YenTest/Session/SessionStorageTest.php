<?php

namespace YenTest\Session;

use Yen\Session\SessionStorage;

class SessionStorageTest extends \PHPUnit_Framework_TestCase
{
    public function testSetGet()
    {
        $storage = new SessionStorage('test');

        $storage->set('foo', 'bar')
                ->set('baz', 'bam');

        $this->assertEquals('bar', $storage->get('foo'));
        $this->assertEquals('bam', $storage->get('baz'));
        $this->assertEquals('fall', $storage->get('none', 'fall'));
    }

    public function testHas()
    {
        $storage = new SessionStorage('test');

        $storage->set('foo', 'bar');

        $this->assertTrue($storage->has('foo'));
        $this->assertFalse($storage->has('none'));
    }

    public function testExtract()
    {
        $storage = new SessionStorage('test');

        $storage->set('foo', 'bar');
        $foo = $storage->extract('foo');

        $this->assertEquals('bar', $foo);
        $this->assertFalse($storage->has('foo'));
        $this->assertEquals('fall', $storage->get('foo', 'fall'));
    }
}
