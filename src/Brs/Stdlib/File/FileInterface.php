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
 * @version 1.0 2014-12-18
 */
interface FileInterface
{
    /**
     * @param string|Closure $name
     * @return FileInterface
     */
    public function setName($name);

    /**
     * @return string
     */
    public function getName();

    /**
     * Write file content to php://output
     * @return FileInterface
     */
    public function stream();

    /**
     * Sending file to the browser.
     * Attention! This method calling the exit() function and code after calling this method will not be executed
     * @param string $fileName
     */
    public function sendToBrowser($fileName = null);

    /**
     * @param string $path Full path of file (with filename and extension);
     * @return FileInterface
     * @throws Brs\Stdlib\Exception\LogicException When file is not physical file and $path argument isn't given
     */
    public function save($path = null);

    /**
     * Whether file is physical file
     * @return boolean
     */
    public function isPhysicalFile();

    /**
     * Reading physical file.
     * @return FileInterface
     * @throws Brs\Stdlib\Exception\FileNotFoundException When file not exists
     */
    public function read($path);
}