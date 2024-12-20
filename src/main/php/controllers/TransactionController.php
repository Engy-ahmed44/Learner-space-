<?php

namespace controllers;

use config\Controller;
use repositories\pdo\MySqlPDODatabase;
use dao\NotificationsDAO;
use dao\BundlesDAO;
use dao\CartDAO;
use dao\StudentsDAO;
use domain\enum\BundleOrderTypeEnum;
use domain\enum\OrderDirectionEnum;
use auth\AuthManager;
use dao\TransactionsDAO;

class TransactionController extends Controller
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

    public function open($idTransaction)
    {
        $dbConnection = MySqlPDODatabase::shared();

        $transactionsDao = new TransactionsDAO($dbConnection);
        $transaction = $transactionsDao->getTransactionById($idTransaction);
        $header = array(
            'title' => 'Transaction #' . $transaction->getId() . ' - Learning Platform',
            'styles' => array('TransactionStyle', 'gallery'),
            'robots' => 'index'
        );
        $viewArgs = array(
            'header' => $header,
            'transaction' => $transaction,
            'has_transaction' => false,
            'scripts' => array('TransactionScript')
        );

        if (AuthManager::isLogged()) {
            $student = AuthManager::getLoggedIn($dbConnection);
            $notificationsDao = new NotificationsDAO($dbConnection, $student->getId());


            $viewArgs['notifications'] = array(
                'notifications' => $notificationsDao->getNotifications(10),
                'total_unread' => $notificationsDao->countUnreadNotification()
            );
            $viewArgs['username'] = $student->getName();
            $viewArgs['transaction'] = $transaction;

            $cartDao = new CartDAO($dbConnection);
            $cart = $cartDao->getCart($student->getId());

            $viewArgs['cartItemsCount'] = count($cart->getItems());
        }

        $this->loadTemplate("TransactionView", $viewArgs, AuthManager::isLogged());
    }
}
