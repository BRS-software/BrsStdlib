<?php

/**
 * (c) BRS software - Tomasz Borys <t.borys@brs-software.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Brs\Stdlib\File;

use Closure;

/**
 * @author Tomasz Borys <t.borys@brs-software.pl>
 * @version 1.0
 */
abstract class FileUtils
{
    /**
     * @param string $filename file name to sanitize
     * @param array $options e.g. ['allowNational', 'toLower']
     * @return string safe file name
     */
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

    /**
     * @param string size e.g. 1MB, 2GB, 10KB, 1024
     * @return integer Size in bytes
     */
    public static function sizeToBytes($from)
    {
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

    /**
     * @return string file mime type
     */
    public static function getMimeType($path)
    {
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mime = finfo_file($finfo, $path);
        finfo_close($finfo);
        return $mime;
    }

    /**
     * Sending content to the browser.
     * @param string|Closure $content
     * @param string $contentType
     * @param string attachmentName
     * @param integer $length Length of content
     * @param bool $inline When value is false then file will be sent as attachment
     */
    public static function send($content, $contentType, $attachmentName = null, $length = null, $inline = false)
    {
        header('Pragma: public');
        header('Expires: 0');
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        header('Content-Type: ' . $contentType);
        header('Content-Transfer-Encoding: binary');

        if (null !== $length) {
            header('Content-Length: ' . $length);
        }
        if (!empty($attachmentName) || $inline) {
            header(sprintf('Content-Disposition: %s; filename="%s"', $inline ? 'inline' : 'attachment', $attachmentName));
        }

        if ($content instanceof Closure) {
            $content();
        } else {
            echo $content;
        }
    }

    /**
     * @return the system temporary directory
     */
    public static function getTmpDir()
    {
        if (function_exists('sys_get_temp_dir')) {
            return sys_get_temp_dir();
        } elseif ( ($tmp = getenv('TMP')) || ($tmp = getenv('TEMP')) || ($tmp = getenv('TMPDIR')) ) {
            return realpath($tmp);
        } else {
            return '/tmp';
        }
    }
}