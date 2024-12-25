<?php

namespace panel\auth;

use panel\auth\strategy\AuthenticationStrategy;
use panel\dao\AdminsDAO;
use panel\domain\Admin;
use panel\repositories\Database;

class AuthManager
{
    /**
     * Checks whether an admin is logged.
     *
     * @return      bool If admin is logged
     */
    public static function isLogged(): bool
    {
        return !empty($_SESSION['a_login']);
    }

    /**
     * Checks if login has been successfully or failed.
     *
     * @param       string $email Admin's email
     * @param       string $password Admin's password
     *
     * @return      Admin Information about admin logged in or null if
     * login failed
     */
    public static function login(AuthenticationStrategy $strategy): ?Admin
    {
        $student = $strategy->authenticate();

        if (!empty($student)) {
            $_SESSION['a_login'] = $student->getId();
        }

        return $student;
    }

    /**
     * Gets logged in admin.
     *
     * @param       Database $db Database
     *
     * @return      Admin Admin logged in or null if there is no admin
     * logged in
     */
    public static function getLoggedIn(Database $db): ?Admin
    {
        if (empty($_SESSION['a_login'])) {
            return null;
        }

        $adminsDAO = new AdminsDAO($db);

        return $adminsDAO->get((int) $_SESSION['a_login']);
    }

    /**
     * Logout current admin.
     */
    public static function logout(): void
    {
        unset($_SESSION['a_login']);
    }
}
