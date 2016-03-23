<?php

namespace Yen\Session\Contract;

use Yen\Http\Contract\IServerRequest;

interface ISession
{
    public function start();
    public function stop();
    public function resume(IServerRequest $request);
    public function suspend();
    public function isActive();
    public function getStorage($key);
}
