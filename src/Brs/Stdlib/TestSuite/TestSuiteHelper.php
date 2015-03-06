<?php

/**
 * (c) BRS software - Tomasz Borys <t.borys@brs-software.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Brs\Stdlib\TestSuite;

/**
 * @author Tomasz Borys <t.borys@brs-software.pl>
 * @version 1.0 2015-01-21
 */
abstract class TestSuiteHelper
{
    public static function showImage($file)
    {
        exec(sprintf('feh -. %s', $file));
    }
}