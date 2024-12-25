<?php

namespace panel\auth\strategy;

use panel\domain\Admin;
use panel\auth\AuthManager;

/**
 * Interface for the authentication strategy
 */
interface AuthenticationStrategy
{
    /**
     * Returns the user or null.
     *
     * @return Student|null
     */
    public function authenticate(): ?Admin;
}
