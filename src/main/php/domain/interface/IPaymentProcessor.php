<?php

namespace domain\interface;

interface IPaymentProcessor
{
	public function processPayment($studentId, $bundleId, $amount): bool;
}
