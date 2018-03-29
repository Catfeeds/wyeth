<?php

function classLoader($class)
{
    if(substr($class, 0, 6) === 'CIPay\\') {
        $path = str_replace('\\', DIRECTORY_SEPARATOR, substr($class, 6));
        $file = __DIR__ . '/' . $path . '.php';
        if (file_exists($file)) {
            require_once $file;
        }
    }
}
spl_autoload_register('classLoader');
