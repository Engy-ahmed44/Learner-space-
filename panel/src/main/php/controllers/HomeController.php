<?php

namespace panel\controllers;


use panel\config\Controller;
use panel\domain\Admin;
use panel\auth\AuthManager;


/**
 * Main controller. It will be responsible for admin's main page behavior.
 */
class HomeController extends Controller
{
    //-------------------------------------------------------------------------
    //        Constructor
    //-------------------------------------------------------------------------
    /**
     * Checks whether admin is logged in and if he has authorization to access 
     * the page. If he is not, redirects him to login page.
     */
    public function __construct()
    {
        if (!AuthManager::isLogged()) {
            $this->redirectTo("login");
        }
    }


    //-------------------------------------------------------------------------
    //        Methods
    //-------------------------------------------------------------------------
    /**
     * @Override
     */
    public function index()
    {
        $this->redirectTo("bundles");
    }

    /**
     * Logout current admin and redirects him to login page. 
     */
    public function logout()
    {
        AuthManager::logout();
        $this->redirectTo("login");
    }
}
