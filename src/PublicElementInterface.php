<?php

/**
 * (c) BRS software - Tomasz Borys <t.borys@brs-software.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Brs\Stdlib;

/**
 * @author Tomasz Borys <t.borys@brs-software.pl>
 * @version 1.0
 */
interface PublicElementInterface
{
    /**
     * @return string path to the element
     */
    public function getLocalPath();

    /**
     * @return string path as part of url to the element
     */
    public function getPublicPath();
}