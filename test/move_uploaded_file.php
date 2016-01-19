<?php

namespace YenMock {

    function mufTest(callable $func = null)
    {
        static $test;

        if ($func !== null) {
            $test = $func;
        };

        return $test;
    }
}

namespace Yen\Http {

    function move_uploaded_file($src, $dst)
    {
        $func = \YenMock\mufTest();
        return $func($src, $dst);
    }

}
