<?php

/**
 * (c) BRS software - Tomasz Borys <t.borys@brs-software.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Brs\Stdlib\File\Type;

use Closure;
use Brs\Stdlib\Exception;
use Brs\Stdlib\File\FileInterface;
use Brs\Stdlib\File\SendableFileInterface;
use Brs\Stdlib\File\FileUtils;

/**
 * @author Tomasz Borys <t.borys@brs-software.pl>
 */
class Generic extends AbstractType
{
    protected function copyFile($path)
    {
        if ($this->isTmp()) {
            rename($this->getPath(), $path);
        } else {
            copy($this->getPath(), $path);
        }
    }

    protected function readFile()
    {
        if ($this->isSaved()) {
            return file_get_contents($this->getPath());
        } else {
            return $this->unsavedContents;
        }
    }

    protected function saveFile()
    {
        if ($this->unsavedContents || ! $this->isSaved()) {
            file_put_contents($this->getPath(), $this->unsavedContents, LOCK_EX);
        }
    }
}