<?php

namespace domain;

use domain\bill\IBill;

// Concrete Bill class implementing IBill
class Bill implements IBill
{
    private int $id;
    private int $userId;
    private int $transactionId;
    private float $totalAmount;
    private array $bundles; // Array of Bundle objects
    private string $createdAt;

    public function __construct(
        int $id,
        int $userId,
        int $transactionId,
        float $totalAmount,
        array $bundles,
        string $createdAt
    ) {
        $this->id = $id;
        $this->userId = $userId;
        $this->transactionId = $transactionId;
        $this->totalAmount = $totalAmount;
        $this->bundles = $bundles;
        $this->createdAt = $createdAt;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getUserId(): int
    {
        return $this->userId;
    }

    public function getTransactionId(): int
    {
        return $this->transactionId;
    }

    public function getTotalAmount(): float
    {
        return $this->totalAmount;
    }

    public function getBundles(): array
    {
        return $this->bundles;
    }

    public function getCreatedAt(): string
    {
        return $this->createdAt;
    }
}
