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
interface SendableFileInterface
{
    /**
     * Name of file attachment returned to the browser.
     * @return string
     */
    public function getAttachmentName();

    /**
     * Returns the length in bytes.
     * @return integer
     */
    public function getContentsLength();

    /**
     * Sending contents to the browser.
     * @param string|null $attachmentName
     * @param string|null $contentType
     * @param string|null $inline
     */
    public function sendToBrowser($attachmentName = null, $contentType = null, $inline = false);
}