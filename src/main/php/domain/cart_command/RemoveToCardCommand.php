<?php

namespace domain\cart_command;

use dao\CartDAO;

/**
 * Command to add a bundle to the cart.
 */
class RemoveToCardCommand implements CartCommand
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
        $this->cartDao->removeItem($this->userId, $this->bundleId);
    }
}
