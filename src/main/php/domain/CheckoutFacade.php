<?php

namespace domain;

use auth\AuthManager;
use dao\CartDAO;
use dao\TransactionsDAO;
use dao\BillDAO;
use dao\StudentsDAO;
use domain\PaymentManager;
use domain\strategy\payment\FawryStrategy;
use domain\strategy\payment\PaypalStrategy;
use domain\strategy\payment\CCStrategy;

class CheckoutFacade
{
    private $dbConnection;
    private $studentId;

    public function __construct($dbConnection, $studentId)
    {
        $this->dbConnection = $dbConnection;
        $this->studentId = $studentId;
    }

    /**
     * Handles the entire checkout process.
     *
     * @param string $paymentMethod
     * @return string|null
     */
    public function processCheckout(string $paymentMethod): ?string
    {
        $cartDao = new CartDAO($this->dbConnection, $this->studentId);
        $totalCost = $cartDao->getTotal($this->studentId);

        // Resolve payment strategy
        $transactionsDao = new TransactionsDAO($this->dbConnection);
        $strategy = $this->getPaymentStrategy($paymentMethod, $transactionsDao);

        if (!$strategy) {
            return null;
        }

        // Process payment
        $paymentManager = new PaymentManager($strategy);
        $transactionId = $paymentManager->pay($totalCost);

        if ($transactionId) {
            $this->finalizeCheckout($transactionId, $totalCost, $cartDao);
        }

        return $transactionId;
    }

    /**
     * Resolves the payment strategy based on the method.
     *
     * @param string $method
     * @param TransactionsDAO $transactionsDao
     * @return FawryStrategy|PaypalStrategy|CCStrategy|null
     */
    private function getPaymentStrategy(string $method, TransactionsDAO $transactionsDao)
    {
        return match ($method) {
            'fawry' => new FawryStrategy($this->studentId, $transactionsDao),
            'paypal' => new PaypalStrategy($this->studentId, $transactionsDao),
            'credit' => new CCStrategy($this->studentId, $transactionsDao),
            default => null,
        };
    }

    /**
     * Finalizes the checkout process.
     *
     * @param string $transactionId
     * @param float $totalCost
     * @param CartDAO $cartDao
     * @return void
     */
    private function finalizeCheckout(string $transactionId, float $totalCost, CartDAO $cartDao): void
    {
        $bundles = $cartDao->clearCart($this->studentId);
        $billDao = new BillDAO($this->dbConnection);

        $studentDAO = new StudentsDAO($this->dbConnection, $this->studentId);
        foreach($bundles as $bundle) {
            $studentDAO->addBundle($bundle->getIdBundle());
        }


        $billDao->createBill(
            $this->studentId,
            $transactionId,
            $totalCost,
            $bundles
        );


    }
}
