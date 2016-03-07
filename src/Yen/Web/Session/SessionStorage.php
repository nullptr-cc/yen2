<?php

namespace Yen\Web\Session;

class SessionStorage
{
    protected $prefix;

    public function __construct($prefix)
    {
        $this->prefix = $prefix;
    }

    public function set($key, $value)
    {
        $_SESSION[$this->prefix][$key] = $value;
        return $this;
    }

    public function get($key, $default = null)
    {
        if (!$this->has($key)) {
            return $default;
        };

        return $_SESSION[$this->prefix][$key];
    }

    public function has($key)
    {
        return isset($_SESSION[$this->prefix][$key]);
    }

    public function extract($key)
    {
        $value = $this->get($key);
        unset($_SESSION[$this->prefix][$key]);
        return $value;
    }
}
