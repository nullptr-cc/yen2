<?php

namespace YenTest\Http;

use Yen\Http;

class ServerRequestTest extends \PHPUnit_Framework_TestCase
{
    public function testCreateSimple()
    {
        $srequest = new Http\ServerRequest();
        $this->assertNull($srequest->getMethod());
        $this->assertEquals('/', $srequest->getRequestTarget());
        $this->assertInstanceOf('\Yen\Http\Contract\IUri', $srequest->getUri());
        $this->assertEmpty($srequest->getServerParams());
        $this->assertEmpty($srequest->getCookieParams());
        $this->assertEmpty($srequest->getQueryParams());
        $this->assertEmpty($srequest->getUploadedFiles());
        $this->assertEmpty($srequest->getHeaders());
        $this->assertFalse($srequest->hasHeader('content-type'));
        $this->assertNull($srequest->getHeader('content-type'));
        $this->assertEquals('', $srequest->getHeaderLine('content-type'));
    }

    public function testCreateWithMethod()
    {
        $srequest = new Http\ServerRequest(['REQUEST_METHOD' => 'GET']);
        $this->assertEquals('GET', $srequest->getMethod());

        $srequest = new Http\ServerRequest(['REQUEST_METHOD' => 'PATCH']);
        $this->assertEquals('PATCH', $srequest->getMethod());

        $srequest = new Http\ServerRequest(['REQUEST_method' => 'GET']);
        $this->assertNull($srequest->getMethod());
    }

    public function testCreateWithTarget()
    {
        $srequest = new Http\ServerRequest(['REQUEST_URI' => '/test/index.html']);
        $this->assertEquals('/test/index.html', $srequest->getRequestTarget());
    }

    public function testCreateWithQueryBodyCookies()
    {
        $query = ['foo' => 'bar', 'a' => 123, 'd' => false];
        $body = ['baz' => 'bat', 'b' => -5, 'c' => null];
        $cookies = ['sid' => '112233445566ffabcde', 'lang' => 'en'];
        $srequest = new Http\ServerRequest([], $query, $body, $cookies);
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
        $srequest = new Http\ServerRequest($env);
        $this->assertEquals($expect, $srequest->getHeaders());
        $this->assertEquals('test.net', $srequest->getHeader('host'));
        $this->assertEquals('text/html,text/plain', $srequest->getHeader('accept'));
        $this->assertEquals('Mozilla/4.0', $srequest->getHeader('user-agent'));
        $this->assertEquals('test.net', $srequest->getHeaderLine('host'));
        $this->assertEquals('text/html,text/plain', $srequest->getHeaderLine('accept'));
        $this->assertEquals('Mozilla/4.0', $srequest->getHeaderLine('user-agent'));
    }

    public function testCreateUri()
    {
        $env = [
            'REQUEST_SCHEME' => 'http',
            'HTTP_HOST' => 'test.net',
            'REQUEST_URI' => '/test/index.html?foo=bar'
        ];
        $srequest = new Http\ServerRequest($env);
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
}
