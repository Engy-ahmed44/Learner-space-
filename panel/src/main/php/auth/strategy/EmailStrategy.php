<?php

namespace panel\auth\strategy;

use panel\auth\strategy\AuthenticationStrategy;
use panel\dao\AdminsDAO;
use panel\domain\Admin;
use panel\auth\AuthManager;
use panel\repositories\Database;

class EmailStrategy implements AuthenticationStrategy
{
    private Database $db;
    private string $email;
    private string $password;

    public function __construct(Database $db, string $email, string $password)
    {
        $this->db = $db;
        $this->email = $email;
        $this->password = $password;
    }

    public function authenticate(): ?Admin
    {
        $studentsDao = new AdminsDAO($this->db);
        $student = $studentsDao->login($this->email, $this->password);

        return $student;
    }
}
