<?php

/**
 * (c) BRS software - Tomasz Borys <t.borys@brs-software.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use Brs\Stdlib\Exception\FatalErrorException;
use Brs\Stdlib\Exception\InvalidArgumentException;
use Brs\Stdlib\Exception\OutOfBoundsException;

/**
 * Normally recursive merging of arrays
 * @return array
 */
function &array_merge_recursive_distinct(/*array1, array2, arrayN*/)
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

/**
 * $map = array_map_closure(
 *      $inputArray,
 *      function ($inputArrayValue, $inputArrayKey) {
 *          return $inputArrayValue->toArray();
 *      },
 *      function ($inputArrayValue, $inputArrayKey, $i) {
 *          return $inputArrayValue->getName() . $i;
 *      }
 * );
 *
 * @param array|ArrayAccess $array input array
 * @param Closure $dataFn function creates map value
 * @param Closure $keyFn function creates map key
 * @return array mapped input array
 */
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

/**
 * Moving value to another position (index) in array
 * @param array $array
 * @param integer $from source index
 * @param integer $to destination index
 */
function array_move_value_by_index(array $array, $from = null, $to = null) {
    if (null === $from) {
        $from = count($array) - 1;
    }
    if (!isset($array[$from])) {
        throw new FatalErrorException("Offset $from does not exist");
    }
    if (array_keys($array) !== range(0, count($array) - 1)) {
        throw new FatalErrorException("Invalid array keys");
    }
    if ($to < 0) {
        throw new FatalErrorException("New offset cannot be a negative number");
    }

    $value = $array[$from];
    unset($array[$from]);

    if (null === $to) {
        array_push($array, $value);
    } else {
        $tail = array_splice($array, $to);
        array_push($array, $value);
        $array = array_merge($array, $tail);
    }
    return $array;
}

/**
 * Recursively filters elements of an array
 * @param array $array
 * @param string|array|Closure value(s) to remove from array
 * @param boolean $recursive
 * @return array
 */
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

/**
 * Creates the array indexed by key deriving from the internal another array value.
 * $input = [['a' => 11, 'b' => 12], ['a' => 21, 'b' => 22]];
 * array_use_key_from_value($input, 'a');
 * result is: [11 => ['a' => 11, 'b' => 12], 21 => ['a' => 21, 'b' => 22]]
 * @param array $arr Input array
 * @param string $useKey
 * @return array
 */
function array_use_key_from_value(array &$arr, $useKey)
{
    $tmp = [];
    foreach ($arr as $v) {
        if (array_key_exists($useKey, $v)) {
            $tmp[$v[$useKey]] = $v;
        } else {
            throw new OutOfBoundsException(
                sprintf('Key "%s" not exists in array', $useKey)
            );
        }
    }
    $arr = $tmp;
    return $arr;
}

/**
 * Create an array from more user friendly format than pure json.
 * You can use also pure json on the input.
 *
 * Friendly json e.g.
 * {someKey:1, x: {a: "value"}, y: {b: 123}}
 *
 * Mixed json e.g.
 * {someKey:1, x: {"a": "value"}, "y": {"b": 123}}
 *
 * @param string $friendlyJson
 * @param boolean $assoc
 * @return array
 */
function json_decode_nice($friendlyJson, $assoc = true)
{
    // remove comments
    $friendlyJson = preg_replace('#^\s*\/\/.*#m', '', $friendlyJson);
    // remove unwanted characters and last comma
    $friendlyJson = str_replace(["\n", "\r", '\\'], ['', '', '\\\\'], $friendlyJson);
    // remove last comma
    $friendlyJson = preg_replace('/\,(\s*)(\}|\])/', '$2', $friendlyJson);
    // add apostrophes to text keys
    // $friendlyJson = preg_replace('/([{,]+)(\s*)([^"]+?)\s*:/','$1"$3":', $friendlyJson);
    $friendlyJson = preg_replace('/([{,]+)(\s*)([\w]+?)\s*:/','$1"$3":', $friendlyJson);
    // convert {"name":'map',"type":'component'} to {"name":"map","type":"component"}
    $friendlyJson = str_replace(
        ["{'", ":'", "'}", "',"],
        ['{"', ':"', '"}', '",'],
        $friendlyJson
    );
    return json_decode($friendlyJson, $assoc);
}

/**
 * Serialize the object to scalar value
 * @param object $var
 * @return scalar
 */
function object_to_scalar($var)
{
    if (! is_object($var)) {
        throw new FatalErrorException('Value is not an object');
    }
    if (method_exists($var, 'toScalar')) {
        $scalar = $var->toScalar();
    } elseif (method_exists($var, 'toString')) {
        $scalar = $var->toString();
    } elseif (method_exists($var, '__toString'))  {
        $scalar = $var->__toString();
    } else {
        throw new FatalErrorException(
            sprintf('Impossible convert object class %s to scalar value', get_class($var))
        );
    }
    if (! is_scalar($scalar)) {
        throw new FatalErrorException(
            sprintf('An error occured during serialize object class %s', get_class($var))
        );
    }

    return $scalar;
}

/**
 * @param string $string
 * @param string $encoding
 */
function mb_ucfirst($string, $encoding = "UTF-8")
{
    $strlen = mb_strlen($string, $encoding);
    $firstChar = mb_substr($string, 0, 1, $encoding);
    $then = mb_substr($string, 1, $strlen - 1, $encoding);
    return mb_strtoupper($firstChar, $encoding) . $then;
}

/**
 * @param string $string
 */
function mb_ucwords($string)
{
    return mb_convert_case($str, MB_CASE_TITLE, "UTF-8");
}

/**
 * mkdir skipping current set umask.
 * @param string $path
 * @param boolean $recursive
 * @param int $mode
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

/**
 * Recursive rmdir.
 * @param string $prefix part of the path above which it is impossible to remove
 * @param string $dir to remove
 */
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

/**
 * Copy of directory.
 * @param string $src
 * @param string $dst
 * @param boolean $mkdirRecursive
 * @param int $mkdirMode
 */
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

/**
 * Converts PHP errors and warnings to exceptions.
 * @param integer $errno
 * @param string $errstr
 * @param string $errfile
 * @param integer $errline
 * @throws FatalErrorException
 */
function brs_error_handler($errno, $errstr, $errfile, $errline)
{
    if(in_array($errno, [E_DEPRECATED, E_STRICT])) {
        return false;
    }
    throw new FatalErrorException(
        sprintf('%s in file %s line %s', $errstr, $errfile, $errline)
    );
}

/**
 * Enables converting errors to exceptions
 */
function convert_errors_to_exceptions()
{
    set_error_handler('brs_error_handler');
}

