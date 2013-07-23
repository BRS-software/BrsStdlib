<?php

use Brs\Stdlib\Debug\Debug;

// when autloader is not available
if (! class_exists('Brs\Stdlib\Debug\Debug')) {
    require __DIR__ . '/Brs/Stdlib/Debug/Debug.php';
}

// disable html_error
if (Debug::isTextSapi()) {
    ini_set('html_errors', 0);
}

/**
 * Debug variable.
 *
 * Xdebug flags (set i.e. .htaccess):
 * php_value xdebug.var_display_max_children 10
 * php_value xdebug.var_display_max_depth 6
 * php_value xdebug.var_display_max_data 512
 * php_flag xdebug.overload_var_dump 1
 * php_flag xdebug.cli_color 1
 *
 * @param mixed $var Any variable
 * @param string $label Optional label
 * @param bool $echo Echo debug output string
 * @return string debug output
 */
function dbg($var = null, $label = null, $echo = true)
{
    return Debug::dump($var, $label, $echo, 1);
}

/**
 * Debug and Die
 */
function dbgD($var = null, $label = null, $echo = true)
{
    Debug::dump($var, $label, $echo, 1);
    die(Debug::htmlFormat("die!\n\n", 'color:red'));
}

/**
 * debug All provided variables
 */
function dbgA($var/*[, $var2, $var3, ...]*/)
{
    foreach (func_get_args() as $i => $var) {
        Debug::dump($var, 'var ' . $i, true, false);
    }
    echo Debug::showCall();
}

/**
 * debug All provided variables and Die
 */
function dbgAD(/*[$var1, $var2, $var3, ...]*/)
{
    foreach (func_get_args() as $i => $var) {
        Debug::dump($var, 'var ' . $i, true, false);
    }
    echo Debug::showCall();
    die(Debug::htmlFormat("die!\n\n", 'color:red'));
}

/**
 * debug Object with describe class
 */
function dbgO($var = null, $label = null, $echo = true)
{
    return Debug::dumpObject($var, $label, $echo, false, 1);
}

/**
 * debug Object with describe class and Die
 */
function dbgOD($var = null, $label = null, $echo = true)
{
    Debug::dumpObject($var, $label, $echo, false, 1);
    die(Debug::htmlFormat("die!\n\n", 'color:red'));
}

/**
 * debug Object with extended Full describe class
 */
function dbgOF($var = null, $label = null, $echo = true)
{
    return Debug::dumpObject($var, $label, $echo, true, 1);
}

/**
 * debug Object with extended Full describe class and Die
 */
function dbgOFD($var = null, $label = null, $echo = true)
{
    Debug::dumpObject($var, $label, $echo, true, 1);
    die(Debug::htmlFormat("die!\n\n", 'color:red'));
}
