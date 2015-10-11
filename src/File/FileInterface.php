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
    public function getPath();
    public function getContentType();
    public function setContents($contents);
    public function isSaved();
    public function save();
    public function read();
}