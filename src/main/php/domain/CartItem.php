<?php

namespace domain;

class CartItem
{
    private int $idBundle;
    private string $name;
    private float $price;
    private int $quantity;
    private ?string $logo;

    public function __construct(int $idBundle, string $name, float $price, int $quantity, ?string $logo)
    {
        $this->idBundle = $idBundle;
        $this->name = $name;
        $this->price = $price;
        $this->quantity = $quantity;
        $this->logo = $logo;
    }

    public function getIdBundle(): int
    {
        return $this->idBundle;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getPrice(): float
    {
        return $this->price;
    }

    public function getQuantity(): int
    {
        return $this->quantity;
    }

    public function getLogo(): ?string
    {
        return $this->logo;
    }
}
