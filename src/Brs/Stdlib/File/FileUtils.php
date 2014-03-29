<?php

/**
 * (c) BRS software - Tomasz Borys <t.borys@brs-software.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Brs\Stdlib\File;

/**
 * @author Tomasz Borys <t.borys@brs-software.pl>
 * @version 1.0 2013-03-13
 */
abstract class FileUtils
{
    public static function sanitizeFilename($filename, array $options = [])
    {
        if (! in_array('allowNational', $options)) {
            $filename = iconv('UTF-8', 'ASCII//TRANSLIT', $filename);
        }
        if (in_array('toLower', $options)) {
            $filename = strtolower($filename);
        }
        return preg_replace(['/\s/', '/[^a-zA-Z0-9-_\.]/'], ['_', ''], $filename);
    }

    public static function sizeToBytes($from) {
        $from = trim(strtoupper($from), 'B');
        $number = substr($from, 0, -1);
        switch(substr($from, -1)) {
            case 'K':
                return $number*1024;
            case 'M':
                return $number*pow(1024,2);
            case 'G':
                return $number*pow(1024,3);
            case 'T':
                return $number*pow(1024,4);
            case 'P':
                return $number*pow(1024,5);
            default:
                return (int) $from;
        }
    }
}