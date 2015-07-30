<?php

spl_autoload_register(function($classname) {
    if (strpos($classname, 'Yen\\') === 0) {
        include_once __DIR__ . '/../src/' . str_replace('\\', '/', $classname) . '.php';
    } else {
        return false;
    };
});
