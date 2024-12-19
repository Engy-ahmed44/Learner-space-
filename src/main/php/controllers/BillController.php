<?php

namespace controllers;

use config\Controller;
use repositories\pdo\MySqlPDODatabase;
use dao\NotificationsDAO;
use dao\CartDAO;
use dao\StudentsDAO;
use dao\BillsDAO;
use auth\AuthManager;
use dao\BillDAO;

class BillController extends Controller
{
    //-------------------------------------------------------------------------
    //        Methods
    //-------------------------------------------------------------------------
    /**
     * @Override
     */
    public function index()
    {
        $this->redirectToRoot();
    }

    public function open($idBill)
    {
        $dbConnection = MySqlPDODatabase::shared();

        $billsDao = new BillDAO($dbConnection);
        $bill = $billsDao->getBillById($idBill);

        // Header info for the page
        $header = array(
            'title' => 'Bill #' . $bill->getId() . ' - Learning Platform',
            'styles' => array('BillStyle', 'gallery'),
            'robots' => 'index'
        );

        // View arguments
        $viewArgs = array(
            'header' => $header,
            'bill' => $bill,
            'has_bill' => false,
            'scripts' => array('BillScript')
        );

        // If user is logged in, add additional data
        if (AuthManager::isLogged()) {
            $student = AuthManager::getLoggedIn($dbConnection);
            $notificationsDao = new NotificationsDAO($dbConnection, $student->getId());

            $viewArgs['notifications'] = array(
                'notifications' => $notificationsDao->getNotifications(10),
                'total_unread' => $notificationsDao->countUnreadNotification()
            );
            $viewArgs['username'] = $student->getName();
            $viewArgs['bill'] = $bill;

            // Get cart items count
            $cartDao = new CartDAO($dbConnection);
            $cart = $cartDao->getCart($student->getId());

            $viewArgs['cartItemsCount'] = count($cart->getItems());
        }

        // Load the Bill view template
        $this->loadTemplate("BillView", $viewArgs, AuthManager::isLogged());
    }
}
