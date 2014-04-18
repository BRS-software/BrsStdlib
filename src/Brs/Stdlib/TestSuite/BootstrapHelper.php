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
 * @version 1.0 2013-04-16
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

    public static function requireZF()
    {
        // check if ZF loaded
        if (! class_exists('Zend\Version\Version')) {
            throw new RuntimeException('Zend Framework 2 not found. Run first ./composer.phar install');
        }
    }

    public static function initZF($value='')
    {
        self::requireZF();
        chdir(dirname(dirname(dirname(dirname(getcwd())))));
        $GLOBALS['zfapp'] = \Zend\Mvc\Application::init(require 'config/application.config.php');
        $GLOBALS['zfapp']->getServiceManager()->setAllowOverride(true); // to the possibility of replacing original services by mock objects
    }

    public function initZFForIntegrationTests($watchArg = '--group integration')
    {
        if (false !== strpos(implode(' ', $GLOBALS['argv']), $watchArg)) {
            self::initZF();
        }
    }
}