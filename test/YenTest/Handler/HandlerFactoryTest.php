<?php

namespace YenTest\Handler;

use Yen\Handler;

class HandlerFactoryTest extends \PHPUnit_Framework_TestCase
{
    public function testMakeCustomHandler()
    {
        $dc = new \Yen\Core\DC();
        $factory = new Handler\HandlerFactory($dc, '\YenTest\Handler\%sHandler');
        $handler = $factory->make('custom');
        $this->assertInstanceOf('\YenTest\Handler\CustomHandler', $handler);
    }

    public function testMakeNullHandler()
    {
        $dc = new \Yen\Core\DC();
        $factory = new Handler\HandlerFactory($dc, '\YenTest\Handler\%sHandler');
        $handler = $factory->make('fake');
        $this->assertInstanceOf('\Yen\Handler\NullHandler', $handler);
    }
}
