<?php

namespace domain\bill;

// Define the IBill interface
interface IBill
{
    public function getId(): int;
    public function getUserId(): int;
    public function getTransactionId(): int;
    public function getTotalAmount(): float;
    public function getBundles(): array;
    public function getCreatedAt(): string;
}
