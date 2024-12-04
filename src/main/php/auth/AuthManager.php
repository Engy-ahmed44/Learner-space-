<?php

namespace auth;

use auth\strategy\AuthenticationStrategy;
use dao\StudentsDAO;
use domain\Student;
use repositories\Database;

class AuthManager
{

    //-------------------------------------------------------------------------
    // Methods
    //-------------------------------------------------------------------------
    /**
     * Checks whether a student is logged.
     *
     * @return bool If student is logged
     */
    public static function isLogged(): bool
    {
        return !empty($_SESSION['s_login']);
    }

    public static function getUserId(): ?int
    {
        return $_SESSION['s_login'];
    }

    /**
     * Checks if login has been successfully or failed.
     *
     * @param string $email Student's email
     * @param string $password Student's password
     *
     * @return Student Information about student logged in or null if
     * login failed
     */
    public static function login(AuthenticationStrategy $strategy): ?Student
    {
        $student = $strategy->authenticate();

        if (!empty($student)) {
            $_SESSION['s_login'] = $student->getId();
        }

        return $student;
    }

    /**
     * Gets logged in student.
     *
     * @param Database $db Database
     *
     * @return Student Student logged in or null if there is no student
     * logged in
     */
    public static function getLoggedIn(Database $db): ?Student
    {
        if (empty($_SESSION['s_login'])) {
            return null;
        }

        $studentsDao = new StudentsDAO($db, $_SESSION['s_login']);

        return $studentsDao->get();
    }

    /**
     * Logout current student.
     */
    public static function logout(): void
    {
        unset($_SESSION['s_login']);
    }
}
