<?php

namespace controllers;


use config\Controller;
use repositories\pdo\MySqlPDODatabase;
use domain\Student;
use auth\AuthManager;
use dao\CartDAO;
use domain\enum\GenreEnum;
use dao\StudentsDAO;
use dao\NotificationsDAO;


/**
 * Responsible for the behavior of the SettingsView.
 */
class SettingsController extends Controller
{
    //-------------------------------------------------------------------------
    //        Constructor
    //-------------------------------------------------------------------------
    /**
     * It will check if student is logged; otherwise, redirects him to login
     * page.
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
        $dbConnection = MySqlPDODatabase::shared();
        $student = AuthManager::getLoggedIn($dbConnection);
        $notificationsDao = new NotificationsDAO($dbConnection, $student->getId());
        $header = array(
            'title' => 'Settings - Learning platform',
            'styles' => array('SettingsStyle'),
            'description' => "User settings",
            'robots' => 'noindex'
        );
        $viewArgs = array(
            'header' => $header,
            'scripts' => array("SettingsScript"),
            'username' => $student->getName(),
            'user' => $student,
            'notifications' => array(
                'notifications' => $notificationsDao->getNotifications(10),
                'total_unread' => $notificationsDao->countUnreadNotification()
            )
        );


        $cartDao = new CartDAO($dbConnection);
        $cart = $cartDao->getCart($student->getId());
        $viewArgs['cartItemsCount'] = count($cart->getItems());

        if (isset($_SESSION['cleared'])) {
            $viewArgs['msg'] = "Session has been successfully cleared!";
            unset($_SESSION['cleared']);
        }

        $this->loadTemplate("settings/SettingsView", $viewArgs);
    }

    /**
     * Edits student settings.
     */
    public function edit()
    {
        if ($this->hasEditBeenSent()) {
            $this->updateSettings();
            $this->redirectTo("settings");
        }

        $dbConnection = MySqlPDODatabase::shared();
        $student = AuthManager::getLoggedIn($dbConnection);
        $notificationsDao = new NotificationsDAO($dbConnection, $student->getId());
        $header = array(
            'title' => 'Settings - Update - Learning platform',
            'styles' => array('SettingsStyle'),
            'description' => "User settings",
            'robots' => 'noindex'
        );
        $viewArgs = array(
            'header' => $header,
            'username' => $student->getName(),
            'user' => $student,
            'notifications' => array(
                'notifications' => $notificationsDao->getNotifications(10),
                'total_unread' => $notificationsDao->countUnreadNotification()
            ),
            'msg' => ''
        );

        $this->loadTemplate("settings/SettingsEditView", $viewArgs);
    }

    private function hasEditBeenSent()
    {
        return  !empty($_POST['name']);
    }

    private function updateSettings()
    {
        $dbConnection = MySqlPDODatabase::shared();
        $studentsDao = new StudentsDAO($dbConnection);
        $student = AuthManager::getLoggedIn($dbConnection);

        $student->setGenre(new GenreEnum($_POST['genre']));
        $student->setBirthdate(new \DateTime($_POST['birthdate']));
        $studentsDao->update($student);
    }

    public function clear()
    {
        $dbConnection = MySqlPDODatabase::shared();
        $studentsDao = new StudentsDAO(
            $dbConnection,
            AuthManager::getLoggedIn($dbConnection)->getId()
        );
        $_SESSION['cleared'] = $studentsDao->clearHistory();

        $this->redirectTo("settings");
    }

    public function delete()
    {
        $dbConnection = MySqlPDODatabase::shared();
        $studentsDao = new StudentsDAO(
            $dbConnection,
            AuthManager::getLoggedIn($dbConnection)->getId()
        );

        if ($studentsDao->delete()) {
            $this->redirectToRoot();
        } else {
            $this->redirectTo("settings");
        }
    }


    //-------------------------------------------------------------------------
    //        Ajax
    //-------------------------------------------------------------------------
    /**
     * Updates student photo.
     * 
     * @param       array $_FILES['photo'] Photo information
     * 
     * @return      bool If photo has been successfully updated
     * 
     * @apiNote     Must be called using POST request method
     */
    public function updateProfilePhoto()
    {
        if ($this->getHttpRequestMethod() != 'POST') {
            $this->redirectToRoot();
        }

        $dbConnection = MySqlPDODatabase::shared();
        $studentsDao = new StudentsDAO(
            $dbConnection,
            AuthManager::getLoggedIn($dbConnection)->getId()
        );

        echo $studentsDao->updatePhoto($_FILES['photo']);
    }

    /**
     * Updates student password.
     * 
     * @param       string $_POST['new_password'] New password
     * @param       string $_POST['current_password'] Current password
     * 
     * @return      bool If password has been successfully updated
     * 
     * @apiNote     Must be called using POST request method
     */
    public function updatePassword()
    {
        if ($this->getHttpRequestMethod() != 'POST') {
            $this->redirectToRoot();
        }

        $dbConnection = MySqlPDODatabase::shared();
        $studentsDao = new StudentsDAO(
            $dbConnection,
            AuthManager::getLoggedIn($dbConnection)->getId()
        );

        echo $studentsDao->updatePassword(
            $_POST['current_password'],
            $_POST['new_password']
        );
    }
}
