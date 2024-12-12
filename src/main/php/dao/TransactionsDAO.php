<?php

declare(strict_types=1);

namespace dao;

use domain\transaction\Transaction;
use domain\transaction\states\PendingState;
use domain\transaction\states\CompletedState;
use domain\transaction\states\FailedState;
use domain\transaction\states\TransactionState;
use repositories\Database;

/**
 * Responsible for managing 'transactions' table.
 */
class TransactionsDAO extends DAO
{
    //-------------------------------------------------------------------------
    //        Constructor
    //-------------------------------------------------------------------------
    /**
     * Creates 'transactions' table manager.
     *
     * @param       Database $db Database instance
     */
    public function __construct(Database $db)
    {
        parent::__construct($db);
    }

    //-------------------------------------------------------------------------
    //        Methods
    //-------------------------------------------------------------------------
    /**
     * Adds a new transaction.
     *
     * @param       int $userId User ID
     * @param       float $amount Transaction amount
     * @param       string $status Initial transaction status (default is 'pending')
     */
    public function addTransaction(int $userId, float $amount, string $gateway, string $status = 'pending'): int
    {
        $this->withQuery("
            INSERT INTO transactions (user_id, amount, gateway, state, created_at)
            VALUES (?, ?, ?, ?, NOW())
        ");
        $this->runQueryWithArguments($userId, $amount, $gateway, $status);

        return (int) $this->db->lastInsertId();
    }

    /**
     * Updates the status of a transaction.
     *
     * @param       int $transactionId Transaction ID
     * @param       string $status New transaction status ('pending', 'completed', 'failed')
     */
    public function updateTransactionStatus(int $transactionId, string $status): void
    {
        $this->withQuery("
            UPDATE transactions
            SET state = ?
            WHERE id = ?
        ");
        $this->runQueryWithArguments($status, $transactionId);
    }

    /**
     * Gets a transaction by ID.
     *
     * @param       int $transactionId Transaction ID
     * @return      Transaction|null The transaction or null if not found
     */
    public function getTransactionById(int $transactionId): ?Transaction
    {
        $this->withQuery("
            SELECT id, user_id, amount, state, gateway, created_at
            FROM transactions
            WHERE id = ?
        ");
        $this->runQueryWithArguments($transactionId);

        if (!$this->hasResponseQuery()) {
            return null;
        }

        return $this->parseTransactionResponse($this->getResponseQuery());
    }

    private function parseTransactionResponse(array $transactionData): Transaction
    {
        $state = $this->mapStatusToState($transactionData['state']);
        return new Transaction(
            (int)$transactionData['id'],
            (int)$transactionData['user_id'],
            (float)$transactionData['amount'],
            (string)$transactionData['gateway'],
            $state,
            $this
        );
    }

    /**
     * Maps the transaction status to a corresponding state.
     *
     * @param       string $status Transaction status
     * @return      TransactionState Corresponding state
     */
    private function mapStatusToState(string $status): TransactionState
    {
        return match ($status) {
            'pending' => new PendingState(),
            'completed' => new CompletedState(),
            'failed' => new FailedState(),
            default => throw new \InvalidArgumentException("Invalid status: $status")
        };
    }

    /**
     * Deletes a transaction by ID.
     *
     * @param       int $transactionId Transaction ID
     */
    public function deleteTransaction(int $transactionId): void
    {
        $this->withQuery("
            DELETE FROM transactions
            WHERE id = ?
        ");
        $this->runQueryWithArguments($transactionId);
    }

    /**
     * Gets all transactions for a user.
     *
     * @param       int $userId User ID
     * @return      Transaction[] List of transactions
     */
    public function getTransactionsByUser(int $userId): array
    {
        $this->withQuery("
            SELECT id, user_id, amount, state, gateway, created_at
            FROM transactions
            WHERE user_id = ?
        ");
        $this->runQueryWithArguments($userId);

        if (!$this->hasResponseQuery()) {
            return [];
        }

        $transactions = [];
        foreach ($this->getAllResponseQuery() as $transactionData) {
            $transactions[] = $this->parseTransactionResponse($transactionData);
        }

        return $transactions;
    }

    /**
     * Updates the state of a transaction in the database.
     *
     * @param int $transactionId Transaction ID
     * @param string $state New state of the transaction
     */
    public function updateTransactionState(int $transactionId, string $state): void
    {
        $query = "UPDATE transactions SET state = :state WHERE id = :id";
        $this->withQuery($query);
        $this->runQueryWithArguments([':state' => $state, ':id' => $transactionId]);
    }
}
