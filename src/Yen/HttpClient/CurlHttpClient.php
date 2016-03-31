<?php

namespace Yen\HttpClient;

use Yen\Http\Contract\IRequest;
use Yen\Http\Response;
use Yen\HttpClient\Contract\IHttpClient;

class CurlHttpClient implements IHttpClient
{
    const VERSION = 0.1;

    protected $options = [
        'connect_timeout' => 0,
        'timeout' => 0
    ];

    public function __construct(array $options = [])
    {
        $this->options = array_merge($this->options, $options);
    }

    public function send(IRequest $request)
    {
        $d = curl_init();
        $opts = $this->prepareCurlOptions($request);
        curl_setopt_array($d, $opts);

        $result = curl_exec($d);

        $error = ['code' => 0, 'msg' => ''];
        if ($result === false) {
            $error['code'] = curl_errno($d);
            $error['msg'] = curl_error($d);
        };
        curl_close($d);

        if ($error['code'] != 0) {
            throw new \RuntimeException($error['msg'], $error['code']);
        };

        return $this->prepareResponse($result);
    }

    /**
     * @return array
     */
    protected function prepareCurlOptions(IRequest $request)
    {
        $version = CURL_HTTP_VERSION_1_0;
        if ($request->getProtocolVersion() == '1.1') {
            $version = CURL_HTTP_VERSION_1_1;
        };

        $opts = [
            CURLOPT_URL => $request->getUri()->__toString(),
            CURLOPT_HTTP_VERSION => $version,
            CURLOPT_HEADER => true,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CONNECTTIMEOUT => $this->options['connect_timeout'],
            CURLOPT_TIMEOUT => $this->options['timeout'],
            CURLOPT_USERAGENT => 'curlhttpclient/' . self::VERSION
        ];

        if ($request->getMethod() == IRequest::METHOD_POST) {
            $opts[CURLOPT_POST] = true;
            $opts[CURLOPT_POSTFIELDS] = $request->getBody();
        };

        $headers = [];
        foreach ($request->getHeaders() as $name => $value) {
            $headers[] = sprintf('%s: %s', $name, $value);
        };
        $opts[CURLOPT_HTTPHEADER] = $headers;

        return $opts;
    }

    /**
     * @return IResponse
     */
    protected function prepareResponse($result)
    {
        list($head, $body) = explode("\r\n\r\n", $result);
        $hlines = explode("\r\n", $head);

        $version = '';
        $code = 0;
        $reason = '';
        $headers = [];

        foreach ($hlines as $line) {
            if (strpos($line, 'HTTP/') === 0) {
                $parts = explode(' ', $line, 3);
                $version = substr($parts[0], 5);
                $code = intval($parts[1]);
                $reason = $parts[2];
                continue;
            };

            list($name, $value) = explode(': ', $line, 2);
            $headers[$name] = $value;
        };

        return new Response($code, $headers, $body, $reason, $version);
    }
}
