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
 */
interface FileInterface
{
    /**
     * @return string File path
     */
    public function getPath();

    /**
     * @return string File MIME type
     */
    public function getContentType();

    /**
     * @param string
     * @return FileInterface
     */
    public function setContents($contents);

    /**
     * @return boolean
     */
    public function isSaved();

    /**
     * Save the file.
     * @return FileInterface
     */
    public function save();

    /**
     * Save the file as.
     * @param string $path New file path
     * @return FileInterface
     */
    public function saveAs($path);

    /**
     * @return string
     */
    public function read();
}