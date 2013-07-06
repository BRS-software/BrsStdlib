<?php

namespace Brs\Stdlib;

trait MetadataTrait
{
    public function setMetaDataFromProvider(MetadataProviderInterface $provider)
    {
        $class = get_class($this);

        if ($provider->hasMetadataForClass($class)) {
            $this->setMetaData($provider->getMetadataForClass($class));
        }
        return $this;
    }
}