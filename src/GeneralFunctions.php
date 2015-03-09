<?php

// register_shutdown_function('shutdownFunction');
// function shutDownFunction() {
//     $error = error_get_last();
//     if ($error) {
//         // XXX tu jest problem z ustawianiem nagłówka, bo są już wysłane nagłówki z error i trzeba by ob_start() gdzieś na początku dać, bo nie zawsze jest w deszkę
//         // http://stackoverflow.com/a/10545621/1418773
//         header("HTTP/1.1 500 Internal Server Error");
//     }
// }

/**
 * Funkcja robiąca rekursywnego normalnego merga przekazanych tablic.
 * Funkcja znaleziona na php.net w komentarzach userów.
 *
 * @return array
 */
function &array_merge_recursive_distinct()
{
    $aArrays = func_get_args();
    $aMerged = $aArrays[0];
    for($i = 1; $i < count($aArrays); $i++) {
        if (is_array($aArrays[$i])) {
            foreach ($aArrays[$i] as $key => $val) {
                if (is_array($aArrays[$i][$key])) {
                    if (isset($aMerged[$key])) {
                        $aMerged[$key] = is_array($aMerged[$key]) ? array_merge_recursive_distinct($aMerged[$key], $aArrays[$i][$key]) : $aArrays[$i][$key];
                    } else {
                        $aMerged[$key] = $aArrays[$i][$key];
                    }
                } else {
                    $aMerged[$key] = $val;
                }
            }
        }
    }
    return $aMerged;
}

function array_map_closure($array, Closure $dataFn, Closure $keyFn = null)
{
    $result = array();
    if (null === $keyFn) {
        $keyFn = function ($v, $k, $i) {
            return $i;
        };
    }
    $i = 0;
    foreach ($array as $k => $v) {
        $result[$keyFn($v, $k, $i++)] = $dataFn($v, $k);
    }
    return $result;
}

function array_filter_custom($array, $toRemove, $recursive = false)
{
    if (! is_array($toRemove) && (! $toRemove instanceof Closure)) {
        $toRemove = [$toRemove];
    }
    return array_filter($array, function (&$v) use ($toRemove, $recursive) {
        if ($toRemove instanceof Closure) {
            return $toRemove($v);
        } elseif (in_array($v, $toRemove, true)) {
            return false;
        } elseif ($recursive && is_array($v)) {
            $v = array_filter_custom($v, $toRemove, $recursive);
            return true;
        } else {
            return true;
        }
    });
}

// json_decode_nice('{json:1, x: {"aaa": "A\B\C"}}'
function json_decode_nice($json, $assoc = true) {
    // remove comments
    $json = preg_replace('#^\s*\/\/.*#m', '', $json);
    // remove unwanted characters and last comma
    $json = str_replace(["\n", "\r", '\\'], ['', '', '\\\\'], $json);
    // remove last comma
    $json = preg_replace('/\,(\s*)(\}|\])/', '$2', $json);
    // add apostrophes to text keys
    // $json = preg_replace('/([{,]+)(\s*)([^"]+?)\s*:/','$1"$3":', $json);
    $json = preg_replace('/([{,]+)(\s*)([\w]+?)\s*:/','$1"$3":', $json);
    // convert {"name":'map',"type":'component'} to {"name":"map","type":"component"}
    $json = str_replace(
        ["{'", ":'", "'}", "',"],
        ['{"', ':"', '"}', '",'],
        $json
    );
    // dbgd($json);
    return json_decode($json,$assoc);
}

/**
 * Serializuje obiekt do wartości skalarnej.
 * @param object $var
 * @return scalar
 */
function object_to_scalar($var)
{
    if (! is_object($var))
        trigger_error('Value is not an object', E_USER_ERROR);

    if (method_exists($var, 'toScalar'))        $scalar = $var->toScalar();
    elseif (method_exists($var, '__toString'))  $scalar = $var->__toString();
    elseif (method_exists($var, 'toString'))    $scalar = $var->toString();
    elseif (method_exists($var, 'toValue'))     $scalar = $var->toValue();
    else trigger_error(sprintf('Impossible convert object class %s to scalar value', get_class($var)));

    if (! is_scalar($scalar))
        trigger_error(sprintf('An error occured during serialize object class %s', get_class($var)));

    return $scalar;
}

function mb_ucfirst($string, $encoding = "UTF-8")
{
    $strlen = mb_strlen($string, $encoding);
    $firstChar = mb_substr($string, 0, 1, $encoding);
    $then = mb_substr($string, 1, $strlen - 1, $encoding);
    return mb_strtoupper($firstChar, $encoding) . $then;
}

function mb_ucwords($str) {
    return mb_convert_case($str, MB_CASE_TITLE, "UTF-8");
}


class FatalErrorException extends Exception {}
function brs_error_handler($errno, $errstr, $errfile, $errline) {
    if($errno == E_DEPRECATED) return false;
    if($errno == E_STRICT) return false;
    // ini_set('display_errors', 1);
    // ini_set('log_errors', 1);
    throw new FatalErrorException(sprintf('%s in file %s line %s', $errstr, $errfile, $errline));
}
function convert_errors_to_exceptions() {
    set_error_handler('brs_error_handler');
}

/**
 * mkdir skipping current set umask
 */
function mkdir_fix($path, $recursive = false, $mode = 0771) {
    $oldumask = umask(0);
    if ($recursive) {
        $dirs = explode(DIRECTORY_SEPARATOR , $path);
        if ($dirs[0] === '') {
            array_shift($dirs);
        }
        $count = count($dirs);
        $path = substr($path, 0, 1) === '/' ? '' : getcwd();
        for ($i = 0; $i < $count; ++$i) {
            $path .= DIRECTORY_SEPARATOR . $dirs[$i];
            if (! is_dir($path) && ! mkdir($path, $mode)) {
                return false;
            }
        }
    } elseif (! mkdir($path, $mode)) {
        return false;
    }
    umask($oldumask);
    return true;
}

function rrmdir($prefix, $dir) {
    $prefix = rtrim(trim($prefix), '/');
    $dir = str_replace('..', '', rtrim(trim($dir), '/'));

    if (! is_dir($prefix)) {
        throw new Exception("Path prefix $prefix must be a directory");
    }
    if ($prefix !== substr($dir, 0, strlen($prefix))) {
        throw new Exception("Dir $dir not is a sub directory of $prefix");
    }
    if (! is_dir($dir) || '/' == $dir) {
        throw new Exception('Invalid dir to remove ' . $dir);
    }
    system("rm -rf " . escapeshellarg($dir));
}

function rcopy($src, $dst, $mkdirRecursive = false, $mkdirMode = 0771) {
    $dir = opendir($src);
    mkdir_fix($dst, $mkdirRecursive, $mkdirMode);
    while(false !== ( $file = readdir($dir)) ) {
        if (( $file != '.' ) && ( $file != '..' )) {
            if ( is_dir($src . '/' . $file) ) {
                rcopy($src . '/' . $file,$dst . '/' . $file, $mkdirMode, $mkdirRecursive);
            }
            else {
                copy($src . '/' . $file,$dst . '/' . $file);
            }
        }
    }
    closedir($dir);
}