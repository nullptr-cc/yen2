<?php

namespace YenTest\HttpClient;

use Yen\HttpClient\CurlHttpClient;
use Yen\Http\Contract\IMessage;
use Yen\Http\Contract\IRequest;
use Yen\Http\Contract\IResponse;
use Yen\Http\Uri;
use Yen\Http\Request;

class CurlHttpClientTest extends \PHPUnit_Framework_TestCase
{
    public function testGoogle()
    {
        $uri = Uri::createFromString('https://www.google.com');
        $request = new Request($uri);

        $client = new CurlHttpClient();
        $response = $client->send($request);

        $this->assertInstanceOf(IResponse::class, $response);
    }

    public function testPost()
    {
        $uri = Uri::createFromString('https://www.google.com');
        $request =
            (new Request($uri))
            ->withProtocolVersion(IMessage::HTTP_VERSION_11)
            ->withMethod(IRequest::METHOD_POST)
            ->withHeader('Accept', 'application/json');

        $client = new CurlHttpClient();
        $response = $client->send($request);

        $this->assertInstanceOf(IResponse::class, $response);
    }

    public function testError()
    {
        $this->expectException(\RuntimeException::class);

        $uri = Uri::createFromString('http://127.0.0.1:1234');
        $request = new Request($uri);

        $client = new CurlHttpClient();
        $response = $client->send($request);
    }
}
