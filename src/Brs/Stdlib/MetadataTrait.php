<?php

namespace Brs\Stdlib;

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