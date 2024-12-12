<?php

namespace domain;

use domain\interface\IPaymentProcessor;

class RealPaymentProcessor implements IPaymentProcessor
{
	public function processPayment($studentId, $bundleId, $amount): bool
	{
		// should sleep for 2 seconds to simulate payment processing
		return true;
	}
}
