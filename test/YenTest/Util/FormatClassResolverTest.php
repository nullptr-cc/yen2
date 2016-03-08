<?php

namespace YenTest\Util;

use Yen\Util\FormatClassResolver;

class FormatClassResolverTest extends \PHPUnit_Framework_TestCase
{
    public function testResolve()
    {
        $resolver = new FormatClassResolver('\\Yen\\%s');
        $classname = $resolver->resolve('Util/FormatClassResolver');

        $this->assertEquals('\Yen\Util\FormatClassResolver', $classname);
    }

    public function testFallback()
    {
        $resolver = new FormatClassResolver('\\Foo\\%s', 'ArrayObject');
        $classname = $resolver->resolve('Bar');

        $this->assertEquals('ArrayObject', $classname);
    }
}
