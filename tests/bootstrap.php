<?php
chdir(__DIR__);

ini_set('xdebug.var_display_max_depth', 5);


// try use composer autload file
$findUpMaxDepth = 5;
$path = __DIR__;
while ($findUpMaxDepth--) {
    $path .= '/..';
    $autoloadFile = $path . '/vendor/autoload.php';
    if (file_exists($autoloadFile)) {
        include $autoloadFile;
        break;
    }
}
// check if ZF loaded
if (! class_exists('Zend\Version\Version')) {
    throw new RuntimeException('Zend Framework 2 not found. Run first ./composer.phar install');
}

if (class_exists('Brs\Stdlib\Debug\Debug')) {
    Brs\Stdlib\Debug\Debug::registerFunctions();
}