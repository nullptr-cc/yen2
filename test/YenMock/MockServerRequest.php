<?php

namespace YenMock;

trait MockServerRequest
{
    protected function mockServerRequest($get = [], $post = [], $hdrs = [])
    {
        $srequest = $this->getMockBuilder('\Yen\Http\ServerRequest')
                         ->disableOriginalConstructor()
                         ->getMock();

        $srequest->method('getQueryParams')->willReturn($get);
        $srequest->method('getParsedBody')->willReturn($post);
        $srequest->method('getHeaders')->willReturn($hdrs);

        return $srequest;
    }
}
