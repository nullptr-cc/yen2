<?php

namespace YenMock;

trait MockHttpResponse
{
    protected function mockHttpResponse($code, array $headers = [], $body = '')
    {
        $response = $this->getMockBuilder('\Yen\Http\Response')
                         ->disableOriginalConstructor()
                         ->getMock();

        $response->method('getStatusCode')->willReturn($code);
        $response->method('getHeaders')->willReturn($headers);
        $response->method('getBody')->willReturn($body);

        return $response;
    }
}
