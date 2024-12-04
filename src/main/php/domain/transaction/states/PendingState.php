<?php

namespace domain\transaction\states;

use domain\transaction\Transaction;
use dao\TransactionsDAO;

/**
 * Represents a pending transaction state.
 */
class PendingState implements TransactionState
{
    public function process(Transaction $transaction): bool
    {
        // Processing logic

        // Simulate always succeeding for now
        return $this->complete($transaction);
    }

    public function complete(Transaction $transaction): bool
    {

        // Transition to CompletedState
        $transaction->setState(new CompletedState());

        // Update the state in the database using TransactionsDAO
        $transactionsDAO = $transaction->getDAO();
        $transactionsDAO->updateTransactionState($transaction->getId(), 'completed');

        return true;
    }

    public function fail(Transaction $transaction): bool
    {

        // Transition to FailedState
        $transaction->setState(new FailedState());

        // Update the state in the database using TransactionsDAO
        $transactionsDAO = $transaction->getDAO();
        $transactionsDAO->updateTransactionState($transaction->getId(), 'failed');

        return true;
    }
}
