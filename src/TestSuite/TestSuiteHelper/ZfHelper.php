<?php

/**
 * (c) BRS software - Tomasz Borys <t.borys@brs-software.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Brs\Stdlib\TestSuite\TestSuiteHelper;

use RuntimeException;
use Zend\ServiceManager\ServiceManager;
use Zend\Mvc\Service\ServiceManagerConfig;
use Zend\Mvc\Application;
use Brs\Stdlib\TestSuite\TestSuiteHelper;

/**
 * @author Tomasz Borys <t.borys@brs-software.pl>
 * @version 1.0
 */
abstract class ZfHelper extends  TestSuiteHelper
{
    public static function chdirToAppRoot()
    {
        chdir(dirname(dirname(self::findAppConfigPath())));
    }

    public static function getApplication()
    {
        self::assertZf();
        self::chdirToAppRoot();
        $configPath = self::findAppConfigPath();
        return Application::init(require $configPath);
    }

    public static function getServiceManager($options = [])
    {
        self::assertZf();

        $options = array_merge([
            'loadModules' => true,
            'chdirToAppRoot' => true,
        ], $options);

        if ($options['chdirToAppRoot']) {
            self::chdirToAppRoot();
        }

        $config = include self::findAppConfigPath();
        $sm = new ServiceManager(new ServiceManagerConfig($config));
        $sm->setService('ApplicationConfig', $config);

        if ($options['loadModules']) {
            $moduleManager = $sm->get('ModuleManager');
            $moduleManager->loadModules();
        }

        return $sm;
    }

    /**
     * Bubble searching the application config.
     */
    public static function findAppConfigPath($dir = null, $maxLookup = 7) {
        $path = $dir ?: getcwd();
        while ($maxLookup--) {
            $configFile = $path . '/config/application.config.php';
            if (file_exists($configFile)) {
                return realpath($configFile);
            }
            $path .= '/..';
        }
        throw new RuntimeException('Application config not found');
    }

    public static function assertZf()
    {
        // check if ZF loaded
        if (! class_exists('Zend\Version\Version')) {
            throw new RuntimeException('Zend Framework 2 not found. Run first ./composer.phar install');
        }
    }
}