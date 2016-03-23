<?php

namespace YenTest\Handler;

use Yen\Util\FormatClassResolver;
use Yen\Handler\HandlerRegistry;
use Yen\Handler\HandlerNotFoundException;

class HandlerRegistryTest extends \PHPUnit_Framework_TestCase
{
    public function testGetHandler()
    {
        $resolver = new FormatClassResolver('\\YenMock\\Handler\\%sHandler');
        $registry = new HandlerRegistry($resolver);

        $handler = $registry->getHandler('custom');

        $this->assertInstanceOf(\YenMock\Handler\CustomHandler::class, $handler);
    }

    public function testGetHandlerException()
    {
        $this->expectException(HandlerNotFoundException::class);

        $resolver = new FormatClassResolver('\\YenMock\\Handler\\%sHandler');
        $registry = new HandlerRegistry($resolver);

        $handler = $registry->getHandler('fake');
    }

    public function testHasHandler()
    {
        $resolver = new FormatClassResolver('\\YenMock\\Handler\\%sHandler');
        $registry = new HandlerRegistry($resolver);

        $this->assertTrue($registry->hasHandler('custom'));
        $this->assertFalse($registry->hasHandler('fake'));
    }
}
