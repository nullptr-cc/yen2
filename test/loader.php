<?php

set_include_path(
    get_include_path() .
    PATH_SEPARATOR .
    __DIR__ . '/../src' .
    PATH_SEPARATOR .
    __DIR__ . '/../lib' .
    PATH_SEPARATOR .
    __DIR__
);

spl_autoload_register(function ($classname) {
    $filename = str_replace('\\', '/', $classname) . '.php';
    if ($realpath = stream_resolve_include_path($filename)) {
        include_once $realpath;
    } else {
        return false;
    };
});

include_once __DIR__ . '/MicroVFS.php';
include_once __DIR__ . '/FuncMock.php';
