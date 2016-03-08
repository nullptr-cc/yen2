<?php

namespace YenMock {

    function mock_func($name, callable $func = null)
    {
        static $mocks = [];

        if ($func !== null) {
            $mocks[$name] = $func;
        };

        return $mocks[$name];
    }

}

namespace Yen\Web\Session {

    function session_status()
    {
        $func = \YenMock\mock_func('session_status');
        return $func();
    }

    function session_start()
    {
        $func = \YenMock\mock_func('session_start');
        return $func();
    }
}
