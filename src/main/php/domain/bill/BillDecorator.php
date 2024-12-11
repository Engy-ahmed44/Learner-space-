<?php

namespace domain\bill;

// Abstract Decorator class implementing IBill
abstract class BillDecorator implements IBill
{
    protected IBill $bill;

    public function __construct(IBill $bill)
    {
        $this->bill = $bill;
    }

    public function getId(): int
    {
        return $this->bill->getId();
    }

    public function getUserId(): int
    {
        return $this->bill->getUserId();
    }

    public function getTransactionId(): int
    {
        return $this->bill->getTransactionId();
    }

    public function getBundles(): array
    {
        return $this->bill->getBundles();
    }

    public function getCreatedAt(): string
    {
        return $this->bill->getCreatedAt();
    }

    public function getTotalAmount(): float
    {
        return $this->bill->getTotalAmount();
    }
}
