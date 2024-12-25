<?php

namespace panel\auth\strategy;

use panel\auth\strategy\AuthenticationStrategy;
use panel\dao\AdminsDAO;
use panel\domain\Admin;
use panel\auth\AuthManager;
use panel\repositories\Database;

class FacebookStrategy implements AuthenticationStrategy
{
    private string $token;

    public function __construct(string $token)
    {
        $this->token = $token;
    }

    public function authenticate(): ?Admin
    {

        // TODO: - Add logic

        return null;
    }
}
