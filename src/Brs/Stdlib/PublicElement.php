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
class PublicElement implements PublicElementInterface
{
    protected $localPath;
    protected $publicPath;

    public function __construct($localPath, $publicPath)
    {
        $this->localPath = $localPath;
        $this->publicPath = $publicPath;
    }

    public function getLocalPath()
    {
        return $this->localPath;
    }

    public function getPublicPath()
    {
        return $this->publicPath;
    }
}