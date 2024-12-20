<?php

namespace controllers;

use auth\AuthManager;
use config\Controller;
use repositories\pdo\MySqlPDODatabase;
use dao\DonationDAO;
use dao\CartDAO;
use dao\TransactionsDAO;
use dao\NotificationsDAO;
use dao\StudentsDAO;
use domain\PaymentManager;
use domain\strategy\payment\CCStrategy;
use domain\strategy\payment\FawryStrategy;
use domain\strategy\payment\PaypalStrategy;
use models\Donation;

class DonationController extends Controller
{
    /**
     * @Override
     */
    public function index()
    {
        $dbConnection = MySqlPDODatabase::shared();

        $header = array(
            'title' => 'Donation - Learning Platform',
            'styles' => array('DonateStyle', 'gallery'),
            'robots' => 'index'
        );
        $viewArgs = array(
            'header' => $header,
            'scripts' => array('DonateScript')
        );

        if (AuthManager::isLogged()) {
            $student = AuthManager::getLoggedIn($dbConnection);
            $studentsDao = new StudentsDAO($dbConnection, $student->getId());
            $notificationsDao = new NotificationsDAO($dbConnection, $student->getId());

            $viewArgs['notifications'] = array(
                'notifications' => $notificationsDao->getNotifications(10),
                'total_unread' => $notificationsDao->countUnreadNotification()
            );
            $viewArgs['username'] = $student->getName();

            $cartDao = new CartDAO($dbConnection);
            $cart = $cartDao->getCart($student->getId());
            $viewArgs['cartItemsCount'] = count($cart->getItems());
        }

        $this->loadTemplate("DonateView", $viewArgs, AuthManager::isLogged());
    }

    /**
     * Handle the donation form submission (POST request).
     */
    public function donate()
    {
        // Ensure the user is logged in
        if (!AuthManager::isLogged()) {
            return;
        }

        // Get the logged-in student details
        $dbConnection = MySqlPDODatabase::shared();
        $student = AuthManager::getLoggedIn($dbConnection);
        $studentId = $student->getId();

        // Extract POST data
        $donationAmount = $_POST['donationAmount'] ?? null;
        $paymentMethod = $_POST['paymentMethod'] ?? null;


        $totalCost = $donationAmount; // Assuming the donation amount is equivalent to the total cost

        // Initialize transaction DAO
        $transactionsDao = new TransactionsDAO($dbConnection);

        // Determine payment strategy based on selected method
        if ($paymentMethod == 'fawry') {
            $strategy = new FawryStrategy($studentId, $transactionsDao);
        } elseif ($paymentMethod == 'paypal') {
            $strategy = new PaypalStrategy($studentId, $transactionsDao);
        } elseif ($paymentMethod == 'credit') {
            $strategy = new CCStrategy($studentId, $transactionsDao);
        } else {
        }

        // Use the payment manager to process the donation
        $paymentManager = new PaymentManager($strategy);

        // Process the payment
        $transactionId = $paymentManager->pay($totalCost);
        if ($transactionId != null) {
            $donationDAO = new DonationDAO($dbConnection);
            $donationDAO->createDonation(
                $studentId,
                $donationAmount,
                $transactionId
            );

            // Optionally, create a notification for the user
            // $notificationsDao = new NotificationsDAO($dbConnection, $studentId);
            // $notificationsDao->createNotification(
            //     "Thank you for your donation of $" . number_format($donationAmount, 2),
            //     "Donation Confirmation"
            // );

            $this->redirectTo("donation/thankyou");
        }
    }

    public function thankyou()
    {
        $header = array(
            'title' => 'Thank you - Learning Platform',
            'styles' => array('DonateStyle', 'gallery'),
            'robots' => 'index'
        );
        $viewArgs = array(
            'header' => $header,
            'scripts' => array('DonateScript')
        );

        $dbConnection = MySqlPDODatabase::shared();
        if (AuthManager::isLogged()) {
            $student = AuthManager::getLoggedIn($dbConnection);
            $studentsDao = new StudentsDAO($dbConnection, $student->getId());
            $notificationsDao = new NotificationsDAO($dbConnection, $student->getId());

            $viewArgs['notifications'] = array(
                'notifications' => $notificationsDao->getNotifications(10),
                'total_unread' => $notificationsDao->countUnreadNotification()
            );
            $viewArgs['username'] = $student->getName();

            $cartDao = new CartDAO($dbConnection);
            $cart = $cartDao->getCart($student->getId());
            $viewArgs['cartItemsCount'] = count($cart->getItems());
        }

        $this->loadTemplate("DonateTYView", $viewArgs, AuthManager::isLogged());
    }
}
