<?php

namespace domain;

class Cart
{
    private array $items;

    public function __construct(array $items = [])
    {
        $this->items = $items;
    }

    public function getItems(): array
    {
        return $this->items;
    }

    public function addItem(CartItem $item): void
    {
        $this->items[] = $item;
    }

    public function getTotalPrice(): float
    {
        return array_reduce($this->items, function ($total, $item) {
            return $total + $item->getPrice() * $item->getQuantity();
        }, 0);
    }

    public function getTotalQuantity(): int
    {
        return array_reduce($this->items, function ($total, $item) {
            return $total + $item->getQuantity();
        }, 0);
    }
}
