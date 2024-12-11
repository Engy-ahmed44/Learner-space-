<?php

namespace domain\bill;

// Concrete Decorator for Discount functionality
class DiscountedBill extends BillDecorator
{
    private float $discount;

    public function __construct(IBill $bill, float $discount)
    {
        parent::__construct($bill);
        $this->discount = $discount;
    }

    // Override getTotalAmount to apply discount
    public function getTotalAmount(): float
    {
        return $this->bill->getTotalAmount() * (1 - $this->discount);
    }
}
