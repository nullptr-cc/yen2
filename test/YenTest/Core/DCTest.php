<?php

namespace YenTest\Core;

use Yen\Core\DC;

class DCTest extends \PHPUnit_Framework_TestCase
{
    public function testSimple()
    {
        $dc = new DC(['foo' => function() { return 'bar'; }]);
        $this->assertEquals('bar', $dc->foo());
    }

    public function testCallable()
    {
        $dc = new DC(['foo' => function() { return function($p) { return $p . $p; }; }]);
        $this->assertEquals('barbar', $dc->foo('bar'));
    }

    /**
     * @expectedException BadMethodCallException
     * @expectedExceptionMessage Yen\Core\DC::foo
     */
    public function testUndefined()
    {
        $dc = new DC();
        $dc->foo();
    }
}
