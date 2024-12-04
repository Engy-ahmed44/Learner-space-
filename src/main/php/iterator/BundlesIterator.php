<?php

namespace iterator;

use domain\Bundle;
use iterator\Iterator;

class BundlesIterator implements Iterator
{
    private array $bundles;
    private int $position = 0;

    public function __construct(array $bundles, int $position)
    {
        $this->bundles = $bundles;
        $this->position = $position;
    }

    public function hasNext(): bool
    {
        return $this->position < count($this->bundles);
    }

    public function current(): mixed
    {
        return $this->bundles[$this->position];
    }

    public function next(): mixed
    {
        if (!$this->hasNext()) {
            return null;
        }

        return $this->bundles[$this->position++];
    }

    public function rewind(): mixed
    {
        $this->position = 0;

        return $this->bundles[$this->position];
    }
}