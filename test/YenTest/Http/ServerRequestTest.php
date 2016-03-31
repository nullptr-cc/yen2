<?php

namespace YenTest\Http;

use Yen\Http;
use Yen\Http\Contract\IRequest;
use Yen\Http\Contract\IUri;
use Yen\Http\Contract\IMessage;
use Yen\Http\ServerRequest;

class ServerRequestTest extends \PHPUnit_Framework_TestCase
{
    public function testCreateSimple()
    {
        $srequest = Http\ServerRequest::createFromGlobals();
        $this->assertEquals(IRequest::METHOD_GET, $srequest->getMethod());
        $this->assertEquals('/', $srequest->getRequestTarget());
        $this->assertInstanceOf(IUri::class, $srequest->getUri());
        $this->assertEmpty($srequest->getServerParams());
        $this->assertEmpty($srequest->getCookieParams());
        $this->assertEmpty($srequest->getQueryParams());
        $this->assertEmpty($srequest->getUploadedFiles());
        $this->assertEmpty($srequest->getHeaders());
        $this->assertFalse($srequest->hasHeader('content-type'));
        $this->assertEquals('', $srequest->getHeader('content-type'));
    }

    public function testCreateWithMethod()
    {
        $srequest = Http\ServerRequest::createFromGlobals(['REQUEST_METHOD' => 'GET']);
        $this->assertEquals('GET', $srequest->getMethod());

        $srequest = Http\ServerRequest::createFromGlobals(['REQUEST_METHOD' => 'PATCH']);
        $this->assertEquals('PATCH', $srequest->getMethod());

        $srequest = Http\ServerRequest::createFromGlobals(['REQUEST_method' => 'DELETE']);
        $this->assertEquals('GET', $srequest->getMethod());
    }

    public function testCreateWithTarget()
    {
        $srequest = Http\ServerRequest::createFromGlobals(['REQUEST_URI' => '/test/index.html']);
        $this->assertEquals('/test/index.html', $srequest->getRequestTarget());
    }

    public function testCreateWithProtocolVersion()
    {
        $srequest = ServerRequest::createFromGlobals();
        $this->assertEquals(IMessage::HTTP_VERSION_10, $srequest->getProtocolVersion());

        $srequest = ServerRequest::createFromGlobals(['SERVER_PROTOCOL' => 'HTTP/1.1']);
        $this->assertEquals(IMessage::HTTP_VERSION_11, $srequest->getProtocolVersion());
    }

    public function testCreateWithQueryBodyCookies()
    {
        $query = ['foo' => 'bar', 'a' => 123, 'd' => false];
        $body = ['baz' => 'bat', 'b' => -5, 'c' => null];
        $cookies = ['sid' => '112233445566ffabcde', 'lang' => 'en'];
        $srequest = Http\ServerRequest::createFromGlobals([], $query, $body, $cookies);
        $this->assertEquals($query, $srequest->getQueryParams());
        $this->assertEquals($body, $srequest->getParsedBody());
        $this->assertEquals($cookies, $srequest->getCookieParams());
    }

    public function testCreateWithHeaders()
    {
        $env = [
            'HTTP_HOST' => 'test.net',
            'HTTP_ACCEPT' => 'text/html,text/plain',
            'HTTP_USER_AGENT' => 'Mozilla/4.0'
        ];
        $expect = [
            'host' => 'test.net',
            'accept' => 'text/html,text/plain',
            'user-agent' => 'Mozilla/4.0'
        ];
        $srequest = Http\ServerRequest::createFromGlobals($env);
        $this->assertEquals($expect, $srequest->getHeaders());
        $this->assertEquals('test.net', $srequest->getHeader('host'));
        $this->assertEquals('text/html,text/plain', $srequest->getHeader('accept'));
        $this->assertEquals('Mozilla/4.0', $srequest->getHeader('user-agent'));
    }

    public function testCreateUri()
    {
        $env = [
            'REQUEST_SCHEME' => 'http',
            'HTTP_HOST' => 'test.net',
            'REQUEST_URI' => '/test/index.html?foo=bar'
        ];
        $srequest = Http\ServerRequest::createFromGlobals($env);
        $uri = $srequest->getUri();
        $this->assertInstanceOf('\Yen\Http\Contract\IUri', $uri);
        $this->assertEquals('http', $uri->getScheme());
        $this->assertEquals('test.net', $uri->getHost());
        $this->assertEquals('/test/index.html', $uri->getPath());
        $this->assertEquals('foo=bar', $uri->getQuery());
    }

    public function testFillFilesSingle()
    {
        $files = Http\ServerRequest::fillFiles([
            'file0' => [
                'error' => 0,
                'name' => 'file0.txt',
                'type' => 'text/plain',
                'size' => 1,
                'tmp_name' => '/tmp/php-12345.txt'
            ]
        ]);

        $this->assertInternalType('array', $files);
        $this->assertCount(1, $files);
        $this->assertContainsOnly('array', $files);
        $this->assertArrayHasKey('file0', $files);
        $this->assertCount(1, $files['file0']);
        $this->assertContainsOnlyInstancesOf('\Yen\Http\UploadedFile', $files['file0']);

        $ufile = $files['file0'][0];
        $this->assertEquals(0, $ufile->getError());
        $this->assertEquals('file0.txt', $ufile->getClientFilename());
        $this->assertEquals('text/plain', $ufile->getClientMediaType());
        $this->assertEquals(1, $ufile->getSize());
    }

    public function testFillFilesMultiple()
    {
        $files = Http\ServerRequest::fillFiles([
            'file0' => [
                'error' => [0, 1],
                'name' => ['file0.txt', null],
                'type' => ['text/plain', null],
                'size' => [1, null],
                'tmp_name' => ['/tmp/php-12345.txt', null]
            ],
            'file1' => [
                'error' => 0,
                'name' => 'file1.txt',
                'type' => 'text/plain',
                'size' => 2,
                'tmp_name' => '/tmp/php-67890.txt'
            ]
        ]);

        $this->assertInternalType('array', $files);
        $this->assertCount(2, $files);
        $this->assertContainsOnly('array', $files);
        $this->assertArrayHasKey('file0', $files);
        $this->assertArrayHasKey('file1', $files);
        $this->assertCount(2, $files['file0']);
        $this->assertContainsOnlyInstancesOf('\Yen\Http\UploadedFile', $files['file0']);
        $this->assertCount(1, $files['file1']);
        $this->assertContainsOnlyInstancesOf('\Yen\Http\UploadedFile', $files['file1']);

        $ufile = $files['file0'][0];
        $this->assertEquals(0, $ufile->getError());
        $this->assertEquals('file0.txt', $ufile->getClientFilename());
        $this->assertEquals('text/plain', $ufile->getClientMediaType());
        $this->assertEquals(1, $ufile->getSize());

        $ufile = $files['file0'][1];
        $this->assertEquals(1, $ufile->getError());
        $this->assertNull($ufile->getClientFilename());
        $this->assertNull($ufile->getClientMediaType());
        $this->assertNull($ufile->getSize());

        $ufile = $files['file1'][0];
        $this->assertEquals(0, $ufile->getError());
        $this->assertEquals('file1.txt', $ufile->getClientFilename());
        $this->assertEquals('text/plain', $ufile->getClientMediaType());
        $this->assertEquals(2, $ufile->getSize());
    }

    public function testWithQueryParams()
    {
        $request = Http\ServerRequest::createFromGlobals();
        $request = $request->withQueryParams(['foo' => 'bar']);

        $this->assertSame(['foo' => 'bar'], $request->getQueryParams());

        $request = Http\ServerRequest::createFromGlobals([], ['foo' => 'bar']);
        $request = $request->withQueryParams(['bar' => 'baz']);

        $this->assertSame(['bar' => 'baz'], $request->getQueryParams());
    }

    public function testWithJoinedQueryParams()
    {
        $request = Http\ServerRequest::createFromGlobals();
        $request = $request->withJoinedQueryParams(['foo' => 'bar']);

        $this->assertSame(['foo' => 'bar'], $request->getQueryParams());

        $request = Http\ServerRequest::createFromGlobals([], ['foo' => 'bar']);
        $request = $request->withJoinedQueryParams(['bar' => 'baz']);

        $this->assertSame(['foo' => 'bar','bar' => 'baz'], $request->getQueryParams());
    }

    public function testWithCookieParams()
    {
        $srequest = ServerRequest::createFromGlobals();
        $this->assertEmpty($srequest->getCookieParams());

        $srequest = $srequest->withCookieParams(['foo' => 'bar']);
        $this->assertEquals(['foo' => 'bar'], $srequest->getCookieParams());
    }

    public function testWithUploadedFiles()
    {
        $srequest = ServerRequest::createFromGlobals();
        $this->assertEmpty($srequest->getUploadedFiles());

        $srequest = $srequest->withUploadedFiles(['foo' => ['name' => 'bar.txt']]);
        $this->assertEquals(['foo' => ['name' => 'bar.txt']], $srequest->getUploadedFiles());
    }

    public function testWithParsedBody()
    {
        $srequest = ServerRequest::createFromGlobals();
        $this->assertEmpty($srequest->getParsedBody());

        $srequest = $srequest->withParsedBody(['foo' => 'bar']);
        $this->assertEquals(['foo' => 'bar'], $srequest->getParsedBody());
    }
}
