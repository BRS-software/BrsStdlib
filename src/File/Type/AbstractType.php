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
abstract class AbstractType implements FileInterface, SendableFileInterface
{
    protected $attachmentName;
    protected $path;
    protected $unsavedContents;
    protected $isSaved = false;
    protected $isTmp = false;
    protected $contentType;

    abstract protected function readFile();
    abstract protected function saveFile();
    abstract protected function copyFile($path);

    public function __construct($path = null, $attachmentName = null)
    {
        if ($path) {
            $this->setPath($path);
        }
        if ($attachmentName) {
            $this->setAttachmentName($attachmentName);
        }
    }

    public function __toString()
    {
        return $this->getPath();
    }

    public function __destruct()
    {
        if ($this->isTmp()) {
            unlink($this->getPath());
        }
    }

    final public function read()
    {
        if (! file_exists($path = $this->getPath())) {
            throw new Exception\FileNotFoundException(
                sprintf('File "%s" not found', $path)
            );
        }
        $this->setIsSaved(true);
        $this->unsavedContents = null;
        return $this->readFile();
    }

    final public function save()
    {
        $this->saveFile();
        $this->setIsSaved(true);
        $this->unsavedContents = null;
        return $this;
    }

    final public function saveAs($path)
    {
        if ($this->isSaved()) {
            $this->copyFile($path);
            $this->setPath($path);
        } else {
            $this->setPath($path);
            $this->save();
        }
        return $this;
    }

    public function setAttachmentName($attachmentName)
    {
        $this->attachmentName = $attachmentName;
        return $this;
    }

    public function setPath($path)
    {
        $this->path = $path;
        $this->isTmp = false;
        $this->setIsSaved(file_exists($path));
        return $this;
    }

    public function getPath()
    {
        if (empty($this->path)) {
            $this->setPath(tempnam(FileUtils::getTmpDir(), 'php_tmpfile_'));
            $this->isTmp = true;
        }
        return $this->path;
    }

    public function setContentType($contentType)
    {
        $this->contentType = $contentType;
        return $this;
    }

    public function getContentType()
    {
        if (null === $this->contentType) {
            $this->setContentType(FileUtils::getMimeType($this->getPath()));
        }
        return $this->contentType;
    }

    public function setContents($contents)
    {
        $this->unsavedContents = $contents;
        $this->setIsSaved(false);
        return $this;
    }

    public function isTmp()
    {
        return $this->isTmp;
    }

    public function isSaved()
    {
        return $this->isSaved;
    }

    public function setIsSaved($flag)
    {
        $this->isSaved = (bool) $flag;
        return $this;
    }

    public function stream()
    {
        echo $this->readFile();
        return $this;
    }

    public function getAttachmentName()
    {
        if ($this->attachmentName instanceof Closure) {
            $n = $this->attachmentName;
            $name = $n($this);
        } else {
            $name = $this->attachmentName;
        }
        return (string) $name;
    }

    public function getContentsLength()
    {
        if ($this->isSaved()) {
            return filesize($this->getPath());
        } else {
            return mb_strlen($this->unsavedContents, '8bit');
        }
    }

    public function sendToBrowser($attachmentName = null, $contentType = null, $inline = false)
    {
        FileUtils::send(
            function () {
                $this->stream();
            },
            $contentType ?: $this->getContentType(),
            $attachmentName ?: $this->getAttachmentName(),
            $this->getContentsLength(),
            $inline
        );
    }
}