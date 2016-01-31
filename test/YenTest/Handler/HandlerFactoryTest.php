<?php

namespace YenTest\Handler;

use Yen\Handler;

class HandlerFactoryTest extends \PHPUnit_Framework_TestCase
{
    use \YenMock\MockDependencyContainer;

    public function testMakeCustomHandler()
    {
        $factory = new Handler\HandlerFactory('\YenMock\Handler\%sHandler');
        $handler = $factory->makeHandler('custom');
        $this->assertInstanceOf('\YenMock\Handler\CustomHandler', $handler);
        $handler = $factory->make('custom');
        $this->assertInstanceOf('\YenMock\Handler\CustomHandler', $handler);
    }

    public function testMakeNullHandler()
    {
        $factory = new Handler\HandlerFactory('\YenTest\Handler\%sHandler');
        $handler = $factory->makeHandler('fake');
        $this->assertInstanceOf('\Yen\Handler\MissedHandler', $handler);
        $handler = $factory->make('fake');
        $this->assertInstanceOf('\Yen\Handler\MissedHandler', $handler);
    }

    public function testCanMake()
    {
        $factory = new Handler\HandlerFactory('\YenMock\Handler\%sHandler');
        $this->assertTrue($factory->canMake('custom'));
        $this->assertTrue($factory->canMake('fake'));
    }
}
