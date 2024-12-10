<?php

namespace domain;

use domain\interface\IPaymentStrategy;


class PaymentManager
{
	private $paymentStrategy;

	public function __construct(
		IPaymentStrategy $paymentStrategy
	) {
		$this->paymentStrategy = $paymentStrategy;
	}

	public function pay($amount): ?int
	{
		return $this->paymentStrategy->pay($amount);
	}
}
