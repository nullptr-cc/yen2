<?php

namespace Yen\Handler\Contract;

interface IHandler
{
    public function handle($method, IRequest $request);
}