<?php

namespace auth\strategy;

use domain\Student;
use auth\AuthManager;

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
    public function authenticate(): ?Student;
}
