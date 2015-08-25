<?php

namespace YenTest\Handler;

use Yen\Handler;

class HandlerFactoryTest extends \PHPUnit_Framework_TestCase
{
    use \YenMock\MockDC;

    public function testMakeCustomHandler()
    {
        $dc = $this->mockDC();
        $factory = new Handler\HandlerFactory($dc, '\YenTest\Handler\%sHandler');
        $handler = $factory->makeHandler('custom');
        $this->assertInstanceOf('\YenTest\Handler\CustomHandler', $handler);
    }

    public function testMakeNullHandler()
    {
        $dc = $this->mockDC();
        $factory = new Handler\HandlerFactory($dc, '\YenTest\Handler\%sHandler');
        $handler = $factory->makeHandler('fake');
        $this->assertInstanceOf('\Yen\Handler\MissedHandler', $handler);
    }
}
