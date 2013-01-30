<?php

namespace BrsStdlib;

class Module
{
    public function __construct()
    {
        // include_once __DIR__ . '/src/DebugFunctions.php';
        \Brs\Stdlib\Debug\Debug::registerFunctions();
        include_once __DIR__ . '/src/GeneralFunctions.php';
    }
}
