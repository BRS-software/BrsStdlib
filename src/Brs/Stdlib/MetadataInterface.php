<?php

namespace Brs\Stdlib;

interface MetadataInterface
{
    public function setMetaDataFromProvider(MetadataProviderInterface $provider);
    public function setMetaData(array $metaData);
    public function getMetaData();
}