<?php

namespace domain\transaction;

use dao\TransactionsDAO;
use domain\transaction\states\CompletedState;
use domain\transaction\states\FailedState;
use domain\transaction\states\PendingState;
use domain\transaction\states\TransactionState;

/**
 * Represents a transaction in the system.
 */
class Transaction
{
    private int $id;
    private int $userId;
    private float $amount;
    private string $gateway;
    private TransactionState $state;
    private TransactionsDAO $dao;

    public function __construct(int $id, int $userId, float $amount, string $gateway, TransactionState $state, TransactionsDAO $dao)
    {
        $this->id = $id;
        $this->userId = $userId;
        $this->amount = $amount;
        $this->gateway = $gateway;
        $this->state = $state;
        $this->dao = $dao;
    }

    /**
     * Gets the transaction ID.
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * Gets the user ID associated with the transaction.
     */
    public function getUserId(): int
    {
        return $this->userId;
    }

    /**
     * Gets the transaction amount.
     */
    public function getAmount(): float
    {
        return $this->amount;
    }

    /**
     * Gets the current state of the transaction.
     */
    public function getState(): TransactionState
    {
        return $this->state;
    }

    public function getStateAsString(): string
    {
        if ($this->state instanceof FailedState) {
            return 'failed';
        } elseif ($this->state instanceof PendingState) {
            return 'pending';
        } elseif ($this->state instanceof CompletedState) {
            return 'completed';
        }

        return 'unknown'; // In case there are other states or the state is not recognized
    }

    public function getGateway(): string
    {
        return $this->gateway;
    }

    public function getDAO(): TransactionsDAO
    {
        return $this->dao;
    }

    /**
     * Sets the state of the transaction and updates it in the database.
     */
    public function setState(TransactionState $state): void
    {
        $this->state = $state;
    }

    /**
     * Processes the transaction through the current state.
     */
    public function process(): bool
    {
        return $this->state->process($this);
    }

    /**
     * Marks the transaction as completed.
     */
    public function complete(): bool
    {
        return $this->state->complete($this);
    }

    /**
     * Marks the transaction as failed.
     */
    public function fail(): bool
    {
        return $this->state->fail($this);
    }
}
