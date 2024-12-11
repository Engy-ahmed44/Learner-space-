<?php

namespace domain\bill;

// Concrete Decorator for applying promotions
class Promotion extends BillDecorator
{
    private float $promotionAmount;

    public function __construct(IBill $bill, float $promotionAmount)
    {
        parent::__construct($bill);
        $this->promotionAmount = $promotionAmount;
    }

    // Override getTotalAmount to apply promotion
    public function getTotalAmount(): float
    {
        return $this->bill->getTotalAmount() - $this->promotionAmount;
    }
}
