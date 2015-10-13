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
interface MetadataInterface
{
    /**
     * @param MetadataProviderInterface
     * @return MetadataInterface
     */
    public function setMetadataFromProvider(MetadataProviderInterface $provider);

    /**
     * @param array $metadata metadata for object
     *
     */
    public function setMetadata(array $metadata);

    /**
     * @return array object metadata
     */
    public function getMetadata();
}