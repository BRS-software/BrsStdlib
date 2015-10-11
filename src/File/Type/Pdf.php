<?php

/**
 * (c) BRS software - Tomasz Borys <t.borys@brs-software.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Brs\Stdlib\File\Type;

/**
 * @author Tomasz Borys <t.borys@brs-software.pl>
 * @version 1.0
 */
class Pdf extends Generic
{
    public function getContentType()
    {
        return 'application/pdf';
    }
}