<?php

namespace auth\strategy;

use auth\strategy\AuthenticationStrategy;
use dao\StudentsDAO;
use domain\Student;
use repositories\Database;

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

    public function authenticate(): ?Student
    {
        $studentsDao = new StudentsDAO($this->db);
        $student = $studentsDao->login($this->email, $this->password);

        return $student;
    }
}
