<?php

namespace YenTest\Handler;

use Yen\Handler;

class HandlerRegistryTest extends \PHPUnit_Framework_TestCase
{
    public function testGetHandler()
    {
        $factory = new Handler\HandlerFactory('\\YenMock\\Handler\\%sHandler');
        $registry = new Handler\HandlerRegistry($factory);

        $handler = $registry->getHandler('custom');

        $this->assertInstanceOf(\YenMock\Handler\CustomHandler::class, $handler);
    }
}
