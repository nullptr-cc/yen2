<?php

namespace YenTest\Core;

use Yen\Core\Exception;

class ExceptionTest extends \PHPUnit_Framework_TestCase
{
    public function test()
    {
        $ex0 = new Exception();
        $this->assertEquals(__FILE__, $ex0->getFile());
        $this->assertEquals(__LINE__ - 2, $ex0->getLine());

        $ex1 = new Exception('', 0, null, 'file', 123);
        $this->assertEquals('file', $ex1->getFile());
        $this->assertEquals(123, $ex1->getLine());
    }
}
