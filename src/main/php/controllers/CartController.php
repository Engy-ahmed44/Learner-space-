<?php

namespace controllers;

use auth\AuthManager;
use config\Controller;
use dao\BillDAO;
use repositories\pdo\MySqlPDODatabase;
use dao\CartDAO;
use dao\NotificationsDAO;
use dao\TransactionsDAO;
use domain\cart_command\AddToCartCommand;
use domain\cart_command\CartInvoker;
use domain\cart_command\RemoveToCardCommand;
use domain\CheckoutFacade;
use domain\PaymentManager;
use domain\PaymentProxy;
use domain\strategy\payment\CCStrategy;
use domain\strategy\payment\FawryStrategy;
use domain\strategy\payment\PaypalStrategy;

/**
 * Responsible for the behavior of the CartView.
 */
class CartController extends Controller
{

    //-------------------------------------------------------------------------
    //        Methods
    //-------------------------------------------------------------------------
    /**
     * @Override
     */
    public function index()
    {
        $dbConnection = MySqlPDODatabase::shared();

        if (!AuthManager::isLogged()) {
            header('Location: ' . BASE_URL . "login");
            exit;
        }

        $student = AuthManager::getLoggedIn($dbConnection);
        $cartDao = new CartDAO($dbConnection);
        $cart = $cartDao->getCart($student->getId());
        $totalPrice = $cart->getTotalPrice();

        $header = array(
            'title' => 'Cart - Learning Platform',
            'styles' => array('CartStyle'),
            'description' => 'View your cart items and proceed to checkout.',
            'keywords' => array('learning platform', 'cart', 'checkout'),
            'robots' => 'index'
        );

        $viewArgs = array(
            'header' => $header,
            'cartItems' => $cart->getItems(),
            'totalPrice' => $totalPrice,
            'username' => $student->getName(),
            'scripts' => array('CartScript')
        );

        $notificationsDao = new NotificationsDAO($dbConnection, $student->getId());
        $viewArgs['notifications'] = array(
            'notifications' => $notificationsDao->getNotifications(10),
            'total_unread' => $notificationsDao->countUnreadNotification()
        );

        $viewArgs['cartItemsCount'] = count($cart->getItems());

        $this->loadTemplate("CartView", $viewArgs, true);
    }

    /**
     * Adds a bundle to the cart.
     * 
     * @param       int $_POST['id_bundle'] Bundle ID to add to the cart
     * 
     * @return      void
     * 
     * @apiNote     Must be called using POST request method
     */
    public function add()
    {
        if ($this->getHttpRequestMethod() != 'POST') {
            return;
        }

        if (empty($_POST['id_bundle'])) {
            return;
        }

        if (!AuthManager::isLogged()) {
            header('Location: ' . BASE_URL . "login");
            exit;
        }

        $dbConnection = MySqlPDODatabase::shared();
        $student = AuthManager::getLoggedIn($dbConnection);

        $cartDao = new CartDAO($dbConnection);

        // Create the AddToCartCommand
        $addCommand = new AddToCartCommand($cartDao, $student->getId(), (int)$_POST['id_bundle']);

        // Create the Invoker and execute the command
        $cartInvoker = new CartInvoker();
        $cartInvoker->setCommand($addCommand);
        $cartInvoker->executeCommand();

        echo json_encode(array('success' => true));
    }

    /**
     * Removes a bundle from the cart.
     * 
     * @param       int $_POST['id_bundle'] Bundle ID to remove from the cart
     * 
     * @return      void
     * 
     * @apiNote     Must be called using POST request method
     */
    public function remove()
    {
        if ($this->getHttpRequestMethod() != 'POST') {
            return;
        }

        if (empty($_POST['id_bundle'])) {
            return;
        }

        if (!AuthManager::isLogged()) {
            header('Location: ' . BASE_URL . "login");
            exit;
        }

        $dbConnection = MySqlPDODatabase::shared();
        $student = AuthManager::getLoggedIn($dbConnection);

        $cartDao = new CartDAO($dbConnection);

        // Create the AddToCartCommand
        $addCommand = new RemoveToCardCommand($cartDao, $student->getId(), (int)$_POST['id_bundle']);

        // Create the Invoker and execute the command
        $cartInvoker = new CartInvoker();
        $cartInvoker->setCommand($addCommand);
        $cartInvoker->executeCommand();

        echo BASE_URL . "cart";
    }

    /**
     * Proceeds to checkout.
     * 
     * @return      void
     * 
     * @apiNote     Must be called using POST request method
     */
    public function checkout()
    {
        if ($this->getHttpRequestMethod() != 'POST') {
            return;
        }

        if (!AuthManager::isLogged()) {
            header('Location: ' . BASE_URL . "login");
            exit;
        }

        $paymentMethod = $_POST['method'] ?? null;

        if (empty($paymentMethod)) {
            return;
        }

        $dbConnection = MySqlPDODatabase::shared();
        $student = AuthManager::getLoggedIn($dbConnection);

        $checkoutFacade = new CheckoutFacade($dbConnection, $student->getId());
        $transactionId = $checkoutFacade->processCheckout($paymentMethod);

        // if ($transactionId) {
        //     echo json_encode(['success' => true, 'transactionId' => $transactionId]);
        // } else {
        //     echo json_encode(['success' => false, 'error' => 'Payment failed or invalid method']);
        // }
    }
}
