<?php

namespace panel\controllers;


use panel\config\Controller;
use panel\repositories\pdo\MySqlPDODatabase;
use panel\domain\Admin;
use panel\auth\AuthManager;
use panel\auth\strategy\EmailStrategy;

/**
 * Responsible for the behavior of the LoginView.
 */
class LoginController extends Controller
{
    //-------------------------------------------------------------------------
    //        Methods
    //-------------------------------------------------------------------------
    /**
     * @Override
     */
    public function index()
    {
        $header = array(
            'title' => 'Login - Learning platform',
            'styles' => array('LoginStyle'),
            'robots' => 'index'
        );
        $viewArgs = array(
            'error' => false,
            'msg' => '',
            'header' => $header
        );

        if ($this->hasFormBeenSent()) {
            if ($this->doLogin()) {
                $this->redirectToRoot();
            }

            $viewArgs['error'] = true;
            $viewArgs['msg'] = "Email and / or password incorrect";
        }

        $this->loadTemplate("LoginView", $viewArgs, false);
    }

    private function hasFormBeenSent()
    {
        return !empty($_POST['email']);
    }

    private function doLogin()
    {
        $admin = AuthManager::login(
            new EmailStrategy(
                MySqlPDODatabase::shared(),
                $_POST['email'],
                $_POST['password']
            )
        );

        return !empty($admin);
    }
}
