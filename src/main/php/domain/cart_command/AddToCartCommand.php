<?php

namespace domain\cart_command;

use dao\CartDAO;

/**
 * Command to add a bundle to the cart.
 */
class AddToCartCommand implements CartCommand
{
    private $cartDao;
    private $userId;
    private $bundleId;

    public function __construct(CartDAO $cartDao, $userId, $bundleId)
    {
        $this->cartDao = $cartDao;
        $this->userId = $userId;
        $this->bundleId = $bundleId;
    }

    public function execute()
    {
        $this->cartDao->addItem($this->userId, $this->bundleId, 1);
    }
}
