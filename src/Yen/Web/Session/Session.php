<?php

namespace Yen\Web\Session;

use Yen\Http\Contract\IServerRequest;

class Session implements Contract\ISession
{
    protected $storages;

    public function __construct()
    {
        $this->storages = [];
    }

    public function isActive()
    {
        return session_status() == PHP_SESSION_ACTIVE;
    }

    public function start()
    {
        if ($this->isActive()) {
            throw new \LogicException('Session already started');
        };

        if (!session_start()) {
            throw new \RuntimeException('Can not start session');
        };

        $inner_storage = $this->getInnerStorage();
        if ($inner_storage->has('started_at')) {
            throw new \Exception('Session ID from other session');
        };

        $inner_storage->set('started_at', time());

        return true;
    }

    public function resume(IServerRequest $request)
    {
        if ($this->isActive()) {
            return true;
        };

        $cookies = $request->getCookieParams();
        if (!array_key_exists(session_name(), $cookies)) {
            return false;
        };

        if (!session_start()) {
            throw new \RuntimeException('Can not resume session');
        };

        $inner_storage = $this->getInnerStorage();
        if (!$inner_storage->has('started_at')) {
            $this->stop();
            return false;
        };

        return true;
    }

    public function suspend()
    {
        if (!$this->isActive()) {
            return false;
        };

        session_write_close();
        return true;
    }

    public function stop()
    {
        if (!$this->isActive()) {
            throw new \LogicException('No session to stop');
        };

        $params = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000,
            $params["path"], $params["domain"],
            $params["secure"], $params["httponly"]
        );

        if (!session_destroy()) {
            throw new \RuntimeException('Can not destroy session');
        };

        return true;
    }

    public function getStorage($prefix)
    {
        if ($prefix == '__inner') {
            throw new \LogicException('Access denied to inner session storage');
        };

        return $this->storage($prefix);
    }

    protected function storage($prefix)
    {
        if (!isset($this->storages[$prefix])) {
            $this->storages[$prefix] = new SessionStorage($prefix);
        };

        return $this->storages[$prefix];
    }

    protected function getInnerStorage()
    {
        return $this->storage('__inner');
    }
}
