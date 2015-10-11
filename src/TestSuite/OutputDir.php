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
 * @version 1.0 2014-06-16
 */
class OutputDir
{
    public $path;
    public $name;

    public function __construct($path, $name = '.testOut')
    {
        $this->path = $path;
        $this->name = $name;

        if (is_dir($this->getFullPath())) {
            $this->rm();
        }
        $this->mk();
    }

    public function __toString()
    {
        return $this->getFullPath();
    }

    public function getFullPath()
    {
        return $this->path . '/' . $this->name;
    }

    public function rm()
    {
        rrmdir($this->path, $this->getFullPath());
        return $this;
    }

    public function mk()
    {
        mkdir_fix($this->getFullPath());
        return $this;
    }
}