<?php

namespace Brs\Stdlib;

trait LazyLoadTrait
{
    protected function lazyLoad($propertyName, \Closure $loader)
    {
        if (null === $this->$propertyName) {
            $this->$propertyName = $loader();
        }
        return $this->$propertyName;
    }
}