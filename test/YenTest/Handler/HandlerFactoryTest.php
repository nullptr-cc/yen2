<?php

namespace YenTest\Handler;

use Yen\Handler;

class HandlerFactoryTest extends \PHPUnit_Framework_TestCase
{
    use \YenMock\MockDC;

    public function testMakeCustomHandler()
    {
        $dc = $this->mockDC();
        $factory = new Handler\HandlerFactory($dc, '\YenMock\Handler\%sHandler');
        $handler = $factory->makeHandler('custom');
        $this->assertInstanceOf('\YenMock\Handler\CustomHandler', $handler);
        $handler = $factory->make('custom');
        $this->assertInstanceOf('\YenMock\Handler\CustomHandler', $handler);
    }

    public function testMakeNullHandler()
    {
        $dc = $this->mockDC();
        $factory = new Handler\HandlerFactory($dc, '\YenTest\Handler\%sHandler');
        $handler = $factory->makeHandler('fake');
        $this->assertInstanceOf('\Yen\Handler\MissedHandler', $handler);
        $handler = $factory->make('fake');
        $this->assertInstanceOf('\Yen\Handler\MissedHandler', $handler);
    }

    public function testCanMake()
    {
        $dc = $this->mockDC();
        $factory = new Handler\HandlerFactory($dc, '\YenMock\Handler\%sHandler');
        $this->assertTrue($factory->canMake('custom'));
        $this->assertTrue($factory->canMake('fake'));
    }
}
