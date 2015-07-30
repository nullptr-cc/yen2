<?php

namespace Yen\Http\Contract;

interface IServerRequest
{
    public function getMethod();
    public function getRequestTarget();
    public function getUri();
    public function getServerParams();
    public function getCookieParams();
    public function getQueryParams();
    public function getUploadedFiles();
    public function getParsedBody();
    public function getHeaders();
    public function hasHeader($name);
    public function getHeader($name);
    public function getHeaderLine($name);
}
