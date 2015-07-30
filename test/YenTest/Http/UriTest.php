<?php

namespace YenTest\Http;

use Yen\Http\Uri;

class UriTest extends \PHPUnit_Framework_TestCase
{
    public function testCreateEmpty()
    {
        $uri = new Uri();
        $this->assertNull($uri->getScheme());
        $this->assertNull($uri->getUserinfo());
        $this->assertNull($uri->getHost());
        $this->assertNull($uri->getPort());
        $this->assertNull($uri->getAuthority());
        $this->assertEquals('/', $uri->getPath());
        $this->assertNull($uri->getQuery());
        $this->assertNull($uri->getFragment());
        $this->assertEquals('/', $uri->__toString());
    }

    public function testCreateSimple()
    {
        $uri = new Uri(['scheme' => 'http', 'host' => 'testing.net', 'path' => '/test/index.html']);
        $this->assertEquals('http', $uri->getScheme());
        $this->assertNull($uri->getUserinfo());
        $this->assertEquals('testing.net', $uri->getHost());
        $this->assertNull($uri->getPort());
        $this->assertEquals('testing.net', $uri->getAuthority());
        $this->assertEquals('/test/index.html', $uri->getPath());
        $this->assertNull($uri->getQuery());
        $this->assertNull($uri->getFragment());
        $this->assertEquals('http://testing.net/test/index.html', $uri->__toString());
    }

    public function testCreateFull()
    {
        $uri = new Uri([
            'scheme' => 'http',
            'user' => 'tester',
            'pass' => 'seCrEt',
            'host' => 'testing.net',
            'port' => 8080,
            'path' => '/test/index.html',
            'query' => 'foo=bar&a=12',
            'fragment' => 'test'
        ]);
        $this->assertEquals('http', $uri->getScheme());
        $this->assertEquals('tester:seCrEt', $uri->getUserinfo());
        $this->assertEquals('testing.net', $uri->getHost());
        $this->assertEquals(8080, $uri->getPort());
        $this->assertEquals('tester:seCrEt@testing.net:8080', $uri->getAuthority());
        $this->assertEquals('/test/index.html', $uri->getPath());
        $this->assertEquals('foo=bar&a=12', $uri->getQuery());
        $this->assertEquals('test', $uri->getFragment());
        $this->assertEquals('http://tester:seCrEt@testing.net:8080/test/index.html?foo=bar&a=12#test', $uri->__toString());
    }

    public function testCreateFromStringSimple()
    {
        $uri = Uri::createFromString('http://testing.net/test/index.html');
        $this->assertEquals('http', $uri->getScheme());
        $this->assertNull($uri->getUserinfo());
        $this->assertEquals('testing.net', $uri->getHost());
        $this->assertNull($uri->getPort());
        $this->assertEquals('testing.net', $uri->getAuthority());
        $this->assertEquals('/test/index.html', $uri->getPath());
        $this->assertNull($uri->getQuery());
        $this->assertNull($uri->getFragment());
        $this->assertEquals('http://testing.net/test/index.html', $uri->__toString());
    }

    public function testCreateFromStringFull()
    {
        $uri = Uri::createFromString('http://tester:seCrEt@testing.net:8080/test/index.html?foo=bar&a=12#test');
        $this->assertEquals('http', $uri->getScheme());
        $this->assertEquals('tester:seCrEt', $uri->getUserinfo());
        $this->assertEquals('testing.net', $uri->getHost());
        $this->assertEquals(8080, $uri->getPort());
        $this->assertEquals('tester:seCrEt@testing.net:8080', $uri->getAuthority());
        $this->assertEquals('/test/index.html', $uri->getPath());
        $this->assertEquals('foo=bar&a=12', $uri->getQuery());
        $this->assertEquals('test', $uri->getFragment());
        $this->assertEquals('http://tester:seCrEt@testing.net:8080/test/index.html?foo=bar&a=12#test', $uri->__toString());
    }

    public function testWithScheme()
    {
        $uri = Uri::createFromString('http://testing.net/index.html');
        $new_uri = $uri->withScheme('https');
        $this->assertEquals('http://testing.net/index.html', $uri->__toString());
        $this->assertInstanceOf('Yen\\Http\\Uri', $new_uri);
        $this->assertEquals('https', $new_uri->getScheme());
        $this->assertNull($new_uri->getUserinfo());
        $this->assertEquals('testing.net', $new_uri->getHost());
        $this->assertNull($new_uri->getPort());
        $this->assertEquals('/index.html', $new_uri->getPath());
        $this->assertNull($new_uri->getQuery());
        $this->assertNull($new_uri->getFragment());
        $this->assertEquals('https://testing.net/index.html', $new_uri->__toString());
    }

    public function testWithUserinfo()
    {
        $uri = Uri::createFromString('http://testing.net/index.html');
        $new_uri = $uri->withUserinfo('tester', 'seCrEt');
        $this->assertEquals('http://testing.net/index.html', $uri->__toString());
        $this->assertInstanceOf('Yen\\Http\\Uri', $new_uri);
        $this->assertEquals('http', $new_uri->getScheme());
        $this->assertEquals('tester:seCrEt', $new_uri->getUserinfo());
        $this->assertEquals('testing.net', $new_uri->getHost());
        $this->assertNull($new_uri->getPort());
        $this->assertEquals('/index.html', $new_uri->getPath());
        $this->assertNull($new_uri->getQuery());
        $this->assertNull($new_uri->getFragment());
        $this->assertEquals('http://tester:seCrEt@testing.net/index.html', $new_uri->__toString());
    }

    public function testWithHost()
    {
        $uri = Uri::createFromString('http://testing.net/index.html');
        $new_uri = $uri->withHost('testing.org');
        $this->assertEquals('http://testing.net/index.html', $uri->__toString());
        $this->assertInstanceOf('Yen\\Http\\Uri', $new_uri);
        $this->assertEquals('http', $new_uri->getScheme());
        $this->assertNull($new_uri->getUserinfo());
        $this->assertEquals('testing.org', $new_uri->getHost());
        $this->assertNull($new_uri->getPort());
        $this->assertEquals('/index.html', $new_uri->getPath());
        $this->assertNull($new_uri->getQuery());
        $this->assertNull($new_uri->getFragment());
        $this->assertEquals('http://testing.org/index.html', $new_uri->__toString());
    }

    public function testWithPort()
    {
        $uri = Uri::createFromString('http://testing.net/index.html');
        $new_uri = $uri->withPort(1234);
        $this->assertEquals('http://testing.net/index.html', $uri->__toString());
        $this->assertInstanceOf('Yen\\Http\\Uri', $new_uri);
        $this->assertEquals('http', $new_uri->getScheme());
        $this->assertNull($new_uri->getUserinfo());
        $this->assertEquals('testing.net', $new_uri->getHost());
        $this->assertEquals(1234, $new_uri->getPort());
        $this->assertEquals('/index.html', $new_uri->getPath());
        $this->assertNull($new_uri->getQuery());
        $this->assertNull($new_uri->getFragment());
        $this->assertEquals('http://testing.net:1234/index.html', $new_uri->__toString());
    }

    public function testWithPath()
    {
        $uri = Uri::createFromString('http://testing.net/index.html');
        $new_uri = $uri->withPath('/about.html');
        $this->assertEquals('http://testing.net/index.html', $uri->__toString());
        $this->assertInstanceOf('Yen\\Http\\Uri', $new_uri);
        $this->assertEquals('http', $new_uri->getScheme());
        $this->assertNull($new_uri->getUserinfo());
        $this->assertEquals('testing.net', $new_uri->getHost());
        $this->assertNull($new_uri->getPort());
        $this->assertEquals('/about.html', $new_uri->getPath());
        $this->assertNull($new_uri->getQuery());
        $this->assertNull($new_uri->getFragment());
        $this->assertEquals('http://testing.net/about.html', $new_uri->__toString());
    }

    public function testWithQuery()
    {
        $uri = Uri::createFromString('http://testing.net/index.html');
        $new_uri = $uri->withQuery('foo=bar&a=12');
        $this->assertEquals('http://testing.net/index.html', $uri->__toString());
        $this->assertInstanceOf('Yen\\Http\\Uri', $new_uri);
        $this->assertEquals('http', $new_uri->getScheme());
        $this->assertNull($new_uri->getUserinfo());
        $this->assertEquals('testing.net', $new_uri->getHost());
        $this->assertNull($new_uri->getPort());
        $this->assertEquals('/index.html', $new_uri->getPath());
        $this->assertEquals('foo=bar&a=12', $new_uri->getQuery());
        $this->assertNull($new_uri->getFragment());
        $this->assertEquals('http://testing.net/index.html?foo=bar&a=12', $new_uri->__toString());
    }

    public function testWithFragment()
    {
        $uri = Uri::createFromString('http://testing.net/index.html');
        $new_uri = $uri->withFragment('test');
        $this->assertEquals('http://testing.net/index.html', $uri->__toString());
        $this->assertInstanceOf('Yen\\Http\\Uri', $new_uri);
        $this->assertEquals('http', $new_uri->getScheme());
        $this->assertNull($new_uri->getUserinfo());
        $this->assertEquals('testing.net', $new_uri->getHost());
        $this->assertNull($new_uri->getPort());
        $this->assertEquals('/index.html', $new_uri->getPath());
        $this->assertNull($new_uri->getQuery());
        $this->assertEquals('test', $new_uri->getFragment());
        $this->assertEquals('http://testing.net/index.html#test', $new_uri->__toString());
    }
}
