<?php

namespace controllers;


use config\Controller;
use repositories\pdo\MySqlPDODatabase;
use domain\Student;
use auth\AuthManager;
use dao\CartDAO;
use dao\NotificationsDAO;
use dao\StudentsDAO;


/**
 * Responsible for the behavior of the PurchasesView.
 */
class PurchasesController extends Controller
{
    //-------------------------------------------------------------------------
    //        Constructor
    //-------------------------------------------------------------------------
    /**
     * It will check if student is logged; otherwise, redirects him to home
     * page.
     */
    public function __construct()
    {
        if (!AuthManager::isLogged()) {
            $this->redirectToRoot();
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
        $studentsDao = new StudentsDAO($dbConnection, $student->getId());
        $limit = 10;
        $index = $this->getIndex();
        $offset = $limit * ($index - 1);
        $header = array(
            'title' => 'Purchases - Learning Platform',
            'description' => "Student purchases",
            'robots' => 'noindex'
        );
        $viewArgs = array(
            'header' => $header,
            'username' => $student->getName(),
            'purchases' => $studentsDao->getPurchases($limit, $offset),
            'notifications' => array(
                'notifications' => $notificationsDao->getNotifications(10),
                'total_unread' => $notificationsDao->countUnreadNotification()
            ),
            'totalPages' => ceil($studentsDao->countPurchases() / $limit),
            'currentIndex' => $index
        );


        $cartDao = new CartDAO($dbConnection);
        $cart = $cartDao->getCart($student->getId());
        $viewArgs['cartItemsCount'] = count($cart->getItems());

        $this->loadTemplate("PurchasesView", $viewArgs, AuthManager::isLogged());
    }

    private function getIndex()
    {
        if (!$this->hasIndexBeenSent()) {
            return 1;
        }

        return ((int) $_GET['index']);
    }

    private function hasIndexBeenSent()
    {
        return  !empty($_POST['index']);
    }
}
