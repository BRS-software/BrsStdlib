<?php

/**
 * (c) BRS software - Tomasz Borys <t.borys@brs-software.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Brs\Stdlib\Exception;

/**
 * @author Tomasz Borys <t.borys@brs-software.pl>
 * @version 1.0
 */
class ExceptionTools
{
    public static $varExportPreviewMaxLength = 250;

    private function __construct()
    {
    }

    public static function msg($msg/*[,sprintfArg1, sprintfArg2]*/)
    {
        $args = func_get_args();
        array_shift($args);
        // $args = array_map(array(__CLASS__, 'varExport'), $args);
        $args = array_map(function($v) {
            return is_string($v) ? $v : self::varExport($v);
            // if (is_string($v)) {
            //     return trim($v) ? $v : sprintf('"%s"', $v);
            // } else {
            //     return self::varExport($v);
            // }
            // return self::varExport($v);
        }, $args);
        // mprd($args);
        array_unshift($args, $msg);
        return call_user_func_array('sprintf', $args);
    }

    public static function varExport($var)
    {
        $value = 'n/a';
        $preview = '';

        if (is_bool($var)) {
            $value = $var ? 'true' : 'false';
        } elseif (null === $var) {
            //return 'null';
        } elseif (is_numeric($var)) {
            $value = $var;
        } elseif (is_array($var)) {
            $value = count($var);
            $preview = sprintf('[%s]', preg_replace('/\n\s*|^array \(|\)$/', '', var_export($var, true)));
        } elseif (is_string($var)) {
            $value = strlen($var);
            $preview = sprintf('"%s"', $var);
        } elseif (is_object($var)) {
            $value = get_class($var);
            if (method_exists($var, '__toString')) {
                $preview = $var->__toString();
            }
        }
        if ($preview && self::$varExportPreviewMaxLength < strlen($preview)) {
            $preview = substr($preview, 0, self::$varExportPreviewMaxLength).'...';
        }
        return sprintf('%s(%s)%s', gettype($var), $value, ($preview ? ' '.$preview : ''));
    }
}