<?php

namespace auth\strategy;

use auth\strategy\AuthenticationStrategy;
use dao\StudentsDAO;
use domain\Student;
use repositories\Database;

class GoogleStrategy implements AuthenticationStrategy
{
    private string $token;

    public function __construct(string $token)
    {
        $this->token = $token;
    }

    public function authenticate(): ?Student
    {

        // TODO: - Add logic
        return null;
    }
}
