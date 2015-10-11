<?php

namespace Brs\Stdlib;

interface MetadataProviderInterface
{
    public function hasMetaDataForClass($class);
    public function getMetaDataForClass($class);
}