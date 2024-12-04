<?php

namespace domain\transaction\states;

use domain\transaction\Transaction;

/**
 * Represents a completed transaction state.
 */
class CompletedState implements TransactionState
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
