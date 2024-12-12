<?php

namespace domain\cart_command;

use domain\cart_command\CartCommand;

/**
 * Invoker to execute cart commands.
 */
class CartInvoker
{
    private $command;

    public function setCommand(CartCommand $command)
    {
        $this->command = $command;
    }

    public function executeCommand()
    {
        $this->command->execute();
    }
}
