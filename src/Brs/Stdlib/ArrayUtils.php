<?php

/**
 * (c) BRS software - Tomasz Borys <t.borys@brs-software.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Brs\Stdlib;

use Brs\Stdlib\Exception;

/**
 * @author Tomasz Borys <t.borys@brs-software.pl>
 * @version 1.0 2013-04-26
 */
class ArrayUtils
{
    public static function keyFromVal(&$arr, $useAsKey)
    {
        if (! is_array($arr)) {
            throw new Exception\LogicException('Argument must be an array');
        }

        $tmp = [];
        foreach ($arr as $v) {
            if (array_key_exists($useAsKey, $v)) {
                $tmp[$v[$useAsKey]] = $v;
            } else {
                throw new Exception\LogicException(
                    sprintf('Key "%s" not exists in array', $useAsKey)
                );
            }
        }
        $arr = $tmp;
        return $arr;
    }
}