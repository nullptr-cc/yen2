<?php

namespace YenTest\Handler;

use Yen\Util\FormatClassResolver;
use Yen\Handler\HandlerRegistry;

class HandlerRegistryTest extends \PHPUnit_Framework_TestCase
{
    public function testGetHandler()
    {
        $resolver = new FormatClassResolver('\\YenMock\\Handler\\%sHandler');
        $registry = new HandlerRegistry($resolver);

        $handler = $registry->getHandler('custom');

        $this->assertInstanceOf(\YenMock\Handler\CustomHandler::class, $handler);
    }
}
