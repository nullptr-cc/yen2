<?php

namespace YenTest\Util;

use Yen\ClassResolver\FormatClassResolver;
use Yen\ClassResolver\ClassNotResolved;

class FormatClassResolverTest extends \PHPUnit_Framework_TestCase
{
    public function testResolve()
    {
        $resolver = new FormatClassResolver('\\Yen\\%s');
        $classname = $resolver->resolve('ClassResolver/FormatClassResolver');

        $this->assertEquals('\Yen\ClassResolver\FormatClassResolver', $classname);
    }

    public function testNotResolved()
    {
        $this->expectException(ClassNotResolved::class);
        $this->expectExceptionMessage('Class by string "Bar" not resolved');

        $resolver = new FormatClassResolver('\\Foo\\%s');
        $classname = $resolver->resolve('Bar');
    }
}
