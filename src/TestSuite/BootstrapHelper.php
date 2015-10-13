<?php

/**
 * (c) BRS software - Tomasz Borys <t.borys@brs-software.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Brs\Stdlib\TestSuite;

use RuntimeException;

/**
 * @author Tomasz Borys <t.borys@brs-software.pl>
 * @version 1.0
 */
abstract class BootstrapHelper
{
    /**
     * Bubble searching the composer autoloader.
     */
    public static function findComposerAutoloader($dir, $maxLookup = 5) {
        $path = $dir;
        while ($maxLookup--) {
            $path .= '/..';
            $autoloadFile = $path . '/vendor/autoload.php';
            if (file_exists($autoloadFile)) {
                include $autoloadFile;
                return;
            }
        }
        throw new RuntimeException('Composer autoloader not found');
    }
}