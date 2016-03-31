<?php

namespace YenTest\Http;

use Yen\Http\Contract\IMessage;
use Yen\Http\Message;

class MessageTest extends \PHPUnit_Framework_TestCase
{
    public function testProtocolVersion()
    {
        $message_10 = $this->getMockForAbstractClass(Message::class);
        $this->assertEquals(IMessage::HTTP_VERSION_10, $message_10->getProtocolVersion());

        $message_11 = $message_10->withProtocolVersion(IMessage::HTTP_VERSION_11);
        $this->assertEquals(IMessage::HTTP_VERSION_11, $message_11->getProtocolVersion());
    }

    public function testProtocolVersionException()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid HTTP version: 2.0');

        $message_10 = $this->getMockForAbstractClass(Message::class);
        $message_20 = $message_10->withProtocolVersion('2.0');
    }

    public function testHeaders()
    {
        $message_10 = $this->getMockForAbstractClass(Message::class);

        $this->assertEmpty($message_10->getHeaders());
        $this->assertFalse($message_10->hasHeader('X-Foo'));
        $this->assertEquals('', $message_10->getHeader('X-Foo'));

        $message_foo = $message_10->withHeader('X-Foo', 'bar');

        $this->assertEquals(['X-Foo' => 'bar'], $message_foo->getHeaders());
        $this->assertTrue($message_foo->hasHeader('X-Foo'));
        $this->assertEquals('bar', $message_foo->getHeader('X-Foo'));

        $message_clean = $message_foo->withoutHeader('X-Foo');

        $this->assertEmpty($message_clean->getHeaders());
        $this->assertFalse($message_clean->hasHeader('X-Foo'));
        $this->assertEquals('', $message_clean->getHeader('X-Foo'));
    }
}
