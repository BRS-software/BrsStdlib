<?php

namespace Brs\Stdlib;

interface MetadataInterface
{
    public function setMetadataFromProvider(MetadataProviderInterface $provider);
    public function setMetadata(array $metadata);
    public function getMetadata();
}