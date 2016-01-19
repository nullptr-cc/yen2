<?php

spl_autoload_register(function ($classname) {
    if (strpos($classname, 'Yen\\') === 0) {
        @include_once __DIR__ . '/../src/' . str_replace('\\', '/', $classname) . '.php';
    } elseif (strpos($classname, 'YenTest\\') === 0) {
        @include_once __DIR__ . '/' . str_replace('\\', '/', $classname) . '.php';
    } elseif (strpos($classname, 'YenMock\\') === 0) {
        @include_once __DIR__ . '/' . str_replace('\\', '/', $classname) . '.php';
    } else {
        return false;
    };
});

include_once __DIR__ . '/MicroVFS.php';
include_once __DIR__ . '/move_uploaded_file.php';
