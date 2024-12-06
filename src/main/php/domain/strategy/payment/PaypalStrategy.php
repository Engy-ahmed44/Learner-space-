<?php

namespace domain\strategy\payment;

use dao\TransactionsDAO;
use domain\interface\IPaymentStrategy;

class PaypalStrategy implements IPaymentStrategy
{
	private int $userId;
	private TransactionsDAO $transactionsDao;

	public function __construct(
		int $userId,
		TransactionsDAO $transactionsDao
	) {
		$this->userId = $userId;
		$this->transactionsDao = $transactionsDao;
	}

	public function pay($amount): ?int
	{

		// TODO: - Handle Paypal logic 
		/**
		 * When request is sent to gateway we create a new transaction object
		 */

		return $this->transactionsDao->addTransaction(
			$this->userId,
			$amount,
			"paypal"
		);
	}
}
