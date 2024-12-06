<?php

namespace domain\interface;

interface IPaymentStrategy
{
	/**
	 * Returns the transaction id if succeeded, null otherwise
	 */
	public function pay($amount): ?int;
}
