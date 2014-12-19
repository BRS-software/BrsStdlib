<?php

/**
 * (c) BRS software - Tomasz Borys <t.borys@brs-software.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Brs\Stdlib\File;

use Closure;
use Brs\Stdlib\Exception;

/**
 * @author Tomasz Borys <t.borys@brs-software.pl>
 * @version 1.0 2014-12-18
 */
abstract class AbstractFile implements FileInterface
{
    protected $name;
    protected $path;

    abstract public function getContentType();
    abstract public function saveFile($path);
    abstract public function readFile($path);

    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    public function getName()
    {
        if ($this->name instanceof Closure) {
            $n = $this->name;
            return $n();
        }
        return $this->name;
    }

    public function sendToBrowser($fileName = null)
    {
        header('Content-type: ' . $this->getContentType());
        header(sprintf('Content-Disposition: attachment; filename="%s"', $fileName ?: $this->getName()));
        $this->stream();
        exit();
    }

    public function isPhysicalFile()
    {
        return $this->path !== null;
    }

    final public function save($path = null)
    {
        if ($path === null && !$this->isPhysicalFile()) {
            throw new Exception\LogicException('This is not physical file');
        }
        $this->saveFile($path ?: $this->getPath());
        return $this;
    }

    final public function read($path)
    {
        if (! file_exists($path)) {
            throw new Exception\FileNotFoundException(
                sprintf('File "%s" not found', $path)
            );
        }
        $this->path = $path;
        $this->readFile();
        return $this;
    }
}