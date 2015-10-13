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
trait MetadataTrait
{
    public function setMetadataFromProvider(MetadataProviderInterface $provider)
    {
        $class = get_class($this);

        if ($provider->hasMetadataForClass($class)) {
            $this->setMetadata($provider->getMetadataForClass($class));
        }
        return $this;
    }
}