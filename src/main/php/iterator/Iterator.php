<?php

namespace iterator;


interface Iterator
{
    public function hasNext(): bool;
    public function next(): mixed;
    public function rewind(): mixed;
    public function current(): mixed;
}
