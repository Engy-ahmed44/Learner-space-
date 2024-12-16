<?php

namespace dao;

use domain\Donation;
use repositories\Database;

/**
 * Responsible for managing the 'donations' table.
 */
class DonationDAO extends DAO
{
    /**
     * Creates 'donations' table manager.
     *
     * @param Database $db Database connection
     */
    public function __construct(Database $db)
    {
        parent::__construct($db);
    }

    /**
     * Creates a new donation.
     *
     * @param int $userId User ID
     * @param float $amount Amount of the donation
     * @param string $transactionId Transaction ID for the donation
     * @return int ID of the created donation
     */
    public function createDonation(int $userId, float $amount, string $transactionId): int
    {
        // Insert into the donations table
        $this->withQuery("
            INSERT INTO donations (user_id, amount, transaction_id)
            VALUES (:user_id, :amount, :transaction_id)
        ");
        $this->runQueryWithArguments([
            ':user_id' => $userId,
            ':amount' => $amount,
            ':transaction_id' => $transactionId,
        ]);

        return $this->db->lastInsertId();
    }

    /**
     * Retrieves a donation by ID.
     *
     * @param int $donationId Donation ID
     * @return Donation|null
     */
    public function getDonationById(int $donationId): ?Donation
    {
        // Fetch donation details
        $this->withQuery("
            SELECT id, user_id, amount, transaction_id, created_at
            FROM donations
            WHERE id = :id
        ");
        $this->runQueryWithArguments([':id' => $donationId]);

        if (!$this->hasResponseQuery()) {
            return null;
        }

        $donationData = $this->getResponseQuery();

        return new Donation(
            (int)$donationData['id'],
            (int)$donationData['user_id'],
            (float)$donationData['amount'],
            $donationData['transaction_id'],
            $donationData['created_at']
        );
    }
}
