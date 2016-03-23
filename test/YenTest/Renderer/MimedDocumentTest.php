<?php

namespace YenTest\Renderer;

use Yen\Renderer\MimedDocument;

class MimedDocumentTest extends \PHPUnit_Framework_TestCase
{
    public function testCreateText()
    {
        $doc = MimedDocument::createText('foo');

        $this->assertInstanceOf(MimedDocument::class, $doc);
        $this->assertEquals('text/plain', $doc->mime());
        $this->assertEquals('foo', $doc->content());
        $this->assertEquals('foo', $doc);
    }

    public function testCreateHtml()
    {
        $doc = MimedDocument::createHtml('foo');

        $this->assertInstanceOf(MimedDocument::class, $doc);
        $this->assertEquals('text/html', $doc->mime());
        $this->assertEquals('foo', $doc->content());
        $this->assertEquals('foo', $doc);
    }

    public function testCreateXml()
    {
        $doc = MimedDocument::createXml('foo');

        $this->assertInstanceOf(MimedDocument::class, $doc);
        $this->assertEquals('text/xml', $doc->mime());
        $this->assertEquals('foo', $doc->content());
        $this->assertEquals('foo', $doc);
    }

    public function testCreateJson()
    {
        $doc = MimedDocument::createJson('foo');

        $this->assertInstanceOf(MimedDocument::class, $doc);
        $this->assertEquals('application/json', $doc->mime());
        $this->assertEquals('foo', $doc->content());
        $this->assertEquals('foo', $doc);
    }
}
