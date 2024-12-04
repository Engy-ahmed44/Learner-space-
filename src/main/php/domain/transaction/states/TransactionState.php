<?php

namespace domain\transaction\states;

use domain\transaction\Transaction;

/**
 * Interface for transaction states.
 */
interface TransactionState
{
    public function process(Transaction $transaction): bool;
    public function complete(Transaction $transaction): bool;
    public function fail(Transaction $transaction): bool;
}
