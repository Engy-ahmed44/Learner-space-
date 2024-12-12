<?php

namespace domain;

use dao\PaymentDAO;
use domain\interface\IPaymentProcessor;
use repositories\Database;

class PaymentProxy implements IPaymentProcessor
{
	private $realProcessor;
	private $cache = [];
	private $paymentDAO;

	public function __construct(Database $db)
	{
		$this->realProcessor = new RealPaymentProcessor();
		$this->paymentDAO = new PaymentDAO($db);
		$this->loadCache();
	}


	private function loadCache()
	{
		$cachedPayments = $this->paymentDAO->loadCachedPayments();
		foreach ($cachedPayments as $payment) {
			$cacheKey = $payment['id_student'] . '_' . $payment['id_bundle'];
			$this->cache[$cacheKey] = true;
		}
	}

	public function processPayment($studentId, $bundleId, $amount): bool
	{
		$cacheKey = $studentId . '_' . $bundleId;

		if (isset($this->cache[$cacheKey])) {
			return false;
		}

		if ($this->paymentDAO->addPaymentToCache($studentId, $bundleId)) {
			$this->cache[$cacheKey] = true;
		}

		$result = $this->realProcessor->processPayment($studentId, $bundleId, $amount);

		$this->paymentDAO->removePaymentFromCache($studentId, $bundleId);

		return $result;
	}
}
