<?php

namespace controllers;


use config\Controller;
use domain\Student;
use auth\AuthManager;
use domain\enum\BundleOrderTypeEnum;
use domain\enum\OrderDirectionEnum;
use repositories\pdo\MySqlPDODatabase;
use dao\BundlesDAO;
use dao\CartDAO;
use dao\ClassesDAO;
use dao\CoursesDAO;
use dao\NotificationsDAO;
use dao\HistoricDAO;
use iterator\BundlesIterator;

/**
 * Responsible for the behavior of the HomeView.
 */
class HomeController extends Controller
{
	//-------------------------------------------------------------------------
	//        Methods
	//-------------------------------------------------------------------------
	/**
	 * {@inheritDoc}
	 * @see Controller::index()
	 * 
	 * @Override
	 */
	public function index()
	{
		$dbConnection = MySqlPDODatabase::shared();
		$bundlesDao = new BundlesDAO($dbConnection);
		$coursesDao = new CoursesDAO($dbConnection);
		$header = array(
			'title' => 'Home - Learning Platform',
			'styles' => array('gallery', 'searchBar'),
			'stylesPHP' => array('HomeStyle'),
			'description' => "Start learning today",
			'keywords' => array('learning platform', 'home'),
			'robots' => 'index'
		);
		$viewArgs = array(
			'header' => $header,
			'scripts' => array('gallery', 'HomeScript'),
			'total_bundles' => $bundlesDao->getTotal(),
			'total_courses' => $coursesDao->getTotal(),
			'total_length' => $this->computeTotalLength($dbConnection)
		);

		$allBundles = $bundlesDao->getAll(
			-1,
			-1,
			'',
			new BundleOrderTypeEnum(BundleOrderTypeEnum::SALES),
			new OrderDirectionEnum(OrderDirectionEnum::DESCENDING)
		);

		$viewArgs['bundles'] = new BundlesIterator($allBundles, 0);

		if (AuthManager::isLogged()) {
			$student = AuthManager::getLoggedIn($dbConnection);
			$notificationsDao = new NotificationsDAO($dbConnection, $student->getId());
			$viewArgs['username'] = $student->getName();
			$viewArgs['notifications'] = array(
				'notifications' => $notificationsDao->getNotifications(10),
				'total_unread' => $notificationsDao->countUnreadNotification()
			);

			$cartDao = new CartDAO($dbConnection);
			$cart = $cartDao->getCart($student->getId());
			$viewArgs['cartItemsCount'] = count($cart->getItems());
		} else {
		}

		$this->loadTemplate("HomeView", $viewArgs, AuthManager::isLogged());
	}

	private function computeTotalLength($dbConnection)
	{
		$total = ClassesDAO::getTotal($dbConnection)['total_length'] / 60;

		return number_format($total, 2);
	}

	/**
	 * Logout current student and redirects him to login page.
	 */
	public function logout()
	{
		AuthManager::logout();
		$this->redirectToRoot();
	}


	//-------------------------------------------------------------------------
	//        Ajax
	//-------------------------------------------------------------------------
	/**
	 * Gets student history of the last 7 days.
	 *
	 * @return      string Student historic
	 */
	public function weeklyProgress()
	{
		if ($this->getHttpRequestMethod() != 'POST') {
			$this->redirectToRoot();
		}

		$dbConnection = MySqlPDODatabase::shared();
		$historicDao = new HistoricDAO(
			$dbConnection,
			AuthManager::getLoggedIn($dbConnection)->getId()
		);

		echo json_encode($historicDao->getWeeklyHistory());
	}

	/**
	 * Gets logged in student.
	 *
	 * @return      string Student logged in
	 *
	 * @apiNote     Must be called using POST request method
	 */
	public function getStudentLoggedIn()
	{
		if ($this->getHttpRequestMethod() != 'POST') {
			$this->redirectToRoot();
		}

		echo json_encode(AuthManager::getLoggedIn(MySqlPDODatabase::shared()));
	}
}
