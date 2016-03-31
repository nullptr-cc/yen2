<?php

namespace Yen\Http\Contract;

interface IServerRequest extends IRequest
{
    public function getServerParams();

    public function getQueryParams();
    public function withQueryParams(array $params);
    public function withJoinedQueryParams(array $params);

    public function getParsedBody();
    public function withParsedBody($data);

    public function getCookieParams();
    public function withCookieParams(array $cookies);

    public function getUploadedFiles();
    public function withUploadedFiles(array $files);
}
