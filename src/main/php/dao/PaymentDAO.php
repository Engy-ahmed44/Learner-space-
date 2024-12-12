<?php

declare(strict_types=1);

namespace dao;

use repositories\Database;

class PaymentDAO extends DAO
{
	public function __construct(Database $db)
	{
		parent::__construct($db);
	}

	public function loadCachedPayments(): array
	{
		$this->withQuery("SELECT * FROM payment_cache");
		$this->runQueryWithoutArguments();

		if ($this->hasResponseQuery()) {
			return $this->getAllResponseQuery();
		}

		return [];
	}

	public function addPaymentToCache(int $studentId, int $bundleId): bool
	{
		$this->withQuery("INSERT INTO payment_cache (id_student, id_bundle) VALUES (?, ?)");
		$this->runQueryWithArguments($studentId, $bundleId);

		return $this->hasResponseQuery();
	}

	public function removePaymentFromCache(int $studentId, int $bundleId): bool
	{
		$this->withQuery("DELETE FROM payment_cache WHERE id_student = ? AND id_bundle = ?;");
		$this->runQueryWithArguments($studentId, $bundleId);

		return $this->hasResponseQuery();
	}
}
