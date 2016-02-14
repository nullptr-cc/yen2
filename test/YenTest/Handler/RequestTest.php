<?php

namespace YenTest\Handler;

use Yen\Handler;

class RequestTest extends \PHPUnit_Framework_TestCase
{
    use \YenMock\MockServerRequest;

    public function testArgumentFromEmptyRequest()
    {
        $hrequest = new Handler\Request($this->mockServerRequest());
        $this->assertNull($hrequest->argument('foo'));
        $this->assertEquals('bar', $hrequest->argument('foo', 'bar'));
    }

    public function testArgumentFromCustomArguments()
    {
        $hrequest = new Handler\Request($this->mockServerRequest(), ['foo' => 'bar']);
        $this->assertEquals('bar', $hrequest->argument('foo'));
    }

    public function testArgumentFromRequest()
    {
        $hrequest = new Handler\Request($this->mockServerRequest('GET', ['foo' => 'bar']));
        $this->assertEquals('bar', $hrequest->argument('foo'));
    }

    public function testMergingArguments()
    {
        $hrequest = new Handler\Request(
            $this->mockServerRequest(
                'GET',
                ['foo' => 'bar', 'baz' => 'g-bat', 'bap' => 'g-bam'],
                ['baz' => 'p-bat', 'bap' => 'p-bam']
            ),
            ['bap' => 'a-bam']
        );
        $this->assertEquals('bar', $hrequest->argument('foo'));
        $this->assertEquals('p-bat', $hrequest->argument('baz'));
        $this->assertEquals('a-bam', $hrequest->argument('bap'));
    }

    public function testXtraArgument()
    {
        $srequest = $this->mockServerRequest('GET', [], [], ['x-foo' => 'bar']);
        $hrequest = new Handler\Request($srequest);
        $this->assertEquals('bar', $hrequest->argument('x-foo'));
        $this->assertNull($hrequest->argument('x-baz'));
    }
}
