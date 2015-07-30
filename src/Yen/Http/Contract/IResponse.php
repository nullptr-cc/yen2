<?php

namespace Yen\Http\Contract;

interface IResponse
{
    public function getStatusCode();
    public function getBody();
    public function getHeaders();
}
