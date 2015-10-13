<?php

/**
 * (c) BRS software - Tomasz Borys <t.borys@brs-software.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Brs\Stdlib;

/**
 * @author Tomasz Borys (tobo) <t.borys@brs-software.pl>
 */
interface MetadataProviderInterface
{
    /**
     * @param string $class
     * @return boolean
     */
    public function hasMetaDataForClass($class);

    /**
     * @param string $class
     * @return array metadata for class
     */
    public function getMetaDataForClass($class);
}