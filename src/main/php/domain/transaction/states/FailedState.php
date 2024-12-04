<?php

namespace domain\transaction\states;

use domain\transaction\Transaction;

/**
 * Represents a failed transaction state.
 */
class FailedState implements TransactionState
{
    public function process(Transaction $transaction): bool
    {
        return false;
    }

    public function complete(Transaction $transaction): bool
    {
        return false;
    }

    public function fail(Transaction $transaction): bool
    {
        return false;
    }
}
