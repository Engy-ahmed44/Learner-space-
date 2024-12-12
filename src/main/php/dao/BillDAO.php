<?php

namespace dao;

use domain\Bill;
use domain\Bundle;
use repositories\Database;

/**
 * Responsible for managing 'bills' and 'bill_bundles' tables.
 */
class BillDAO extends DAO
{
    /**
     * Creates 'bills' table manager.
     *
     * @param Database $db Database connection
     */
    public function __construct(Database $db)
    {
        parent::__construct($db);
    }

    /**
     * Creates a new bill.
     *
     * @param int $userId User ID
     * @param int $transactionId Transaction ID
     * @param float $totalAmount Total amount of the bill
     * @param array $bundles Array of bundles (each containing bundle ID, quantity, and price)
     * @return int ID of the created bill
     */
    public function createBill(int $userId, int $transactionId, float $totalAmount, array $bundles): int
    {
        // Insert into the bills table
        $this->withQuery("
            INSERT INTO bills (user_id, transaction_id, total_amount)
            VALUES (:user_id, :transaction_id, :total_amount)
        ");
        $this->runQueryWithArguments([
            ':user_id' => $userId,
            ':transaction_id' => $transactionId,
            ':total_amount' => $totalAmount,
        ]);

        $billId = $this->db->lastInsertId();

        // Insert into the bill_bundles table
        foreach ($bundles as $bundle) {
            $this->withQuery("
                INSERT INTO bill_bundles (bill_id, bundle_id, quantity, price)
                VALUES (:bill_id, :bundle_id, :quantity, :price)
            ");
            $this->runQueryWithArguments([
                ':bill_id' => $billId,
                ':bundle_id' => $bundle->getIdBundle(),
                ':quantity' => $bundle->getQuantity(),
                ':price' => $bundle->getPrice(),
            ]);
        }

        return $billId;
    }

    /**
     * Retrieves a bill by ID.
     *
     * @param int $billId Bill ID
     * @return Bill|null
     */
    public function getBillById(int $billId): ?Bill
    {
        // Fetch bill details
        $this->withQuery("
            SELECT id, user_id, transaction_id, total_amount, created_at
            FROM bills
            WHERE id = :id
        ");
        $this->runQueryWithArguments([':id' => $billId]);

        if (!$this->hasResponseQuery()) {
            return null;
        }

        $billData = $this->getResponseQuery();

        // Fetch associated bundles
        $this->withQuery("
            SELECT bundle_id, quantity, price
            FROM bill_bundles
            WHERE bill_id = :bill_id
        ");
        $this->runQueryWithArguments([':bill_id' => $billId]);

        $bundles = [];
        if ($this->hasResponseQuery()) {
            foreach ($this->getAllResponseQuery() as $bundleData) {
                $bundles[] = new Bundle(
                    $bundleData['bundle_id'],
                    $bundleData['quantity'],
                    $bundleData['price']
                );
            }
        }

        return new Bill(
            (int)$billData['id'],
            (int)$billData['user_id'],
            (int)$billData['transaction_id'],
            (float)$billData['total_amount'],
            $bundles,
            $billData['created_at']
        );
    }
}
