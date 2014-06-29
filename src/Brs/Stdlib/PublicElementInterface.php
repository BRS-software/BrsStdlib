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
 * @version 1.0 2013-01-24
 */
interface PublicElementInterface
{
    public function getLocalPath();
    public function getPublicPath();
}