<?php

namespace domain;

// Concrete Donation class implementing IDonation
class Donation
{
    private int $id;
    private int $userId;
    private float $amount;
    private string $transactionId;
    private string $createdAt;

    public function __construct(
        int $id,
        int $userId,
        float $amount,
        string $transactionId,
        string $createdAt
    ) {
        $this->id = $id;
        $this->userId = $userId;
        $this->amount = $amount;
        $this->transactionId = $transactionId;
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

    public function getAmount(): float
    {
        return $this->amount;
    }

    public function getTransactionId(): string
    {
        return $this->transactionId;
    }

    public function getCreatedAt(): string
    {
        return $this->createdAt;
    }
}
