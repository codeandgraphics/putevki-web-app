<?php

namespace Backend\Controllers;

use Backend\Models\Requests;
use Backend\Models\Payments;
use Backend\Models\Tourists;
use Models\Blog\Posts;
use Models\Origin;
use Utils;

class IndexController extends ControllerBase
{
	public function indexAction()
	{
		$todayDate = new \DateTime();
		$lastDayDate = new \DateTime('-1 day');
		$today = new \stdClass();

		$todayQuery = 'creationDate BETWEEN "' . $todayDate->format('Y-m-d') . ' 00:00:00" AND "' . $todayDate->format('Y-m-d') . ' 23:59:59"';
		$todayPaymentsQuery = 'payDate BETWEEN "' . $todayDate->format('Y-m-d') . ' 00:00:00" AND "' . $todayDate->format('Y-m-d') . ' 23:59:59"';
		$lastDayQuery = 'creationDate BETWEEN "' . $lastDayDate->format('Y-m-d') . ' 00:00:00" AND "' . $lastDayDate->format('Y-m-d') . ' 23:59:59"';
		$lastDayPaymentsQuery = 'payDate BETWEEN "' . $lastDayDate->format('Y-m-d') . ' 00:00:00" AND "' . $lastDayDate->format('Y-m-d') . ' 23:59:59"';

		$webQuery = " AND origin = '" . Origin::WEB . "'";
		$mobileQuery =  " AND (origin = '" . Origin::MOBILE_IOS . "' OR origin = '" . Origin::MOBILE_ANDROID . "' OR origin = '" . Origin::MOBILE . "')";

		//Заявки
		$todayRequests = new \stdClass();
		$todayRequests->count = (int)Requests::count($todayQuery . $webQuery);
		$lastDayRequestsCount = (int)Requests::count($lastDayQuery . $webQuery);
		$todayRequests->diff = Utils\Text::countDiff($todayRequests->count, $lastDayRequestsCount);
		$today->requests = $todayRequests;
		//Заявки

		//Деньги
		$todayPayments = new \stdClass();
		$todayPayments->count = round(Payments::sum([
			'conditions' => $todayPaymentsQuery . " AND (status = 'paid' OR status = 'authorized')",
			'column' => 'sum'
		]));
		$lastDayPaymentsCount = round(Payments::sum([
			'conditions' => $lastDayPaymentsQuery . " AND (status = 'paid' OR status = 'authorized')",
			'column' => 'sum'
		]));
		$todayPayments->diff = Utils\Text::countDiff($todayPayments->count, $lastDayPaymentsCount);
		$today->payments = $todayPayments;
		//Деньги

		//Заявки из приложения
		$todayApps = new \stdClass();
		$todayApps->count = (int)Requests::count($todayQuery  . $mobileQuery);
		$lastDayAppsCount = (int)Requests::count($lastDayQuery  . $mobileQuery);
		$todayApps->diff = Utils\Text::countDiff($todayApps->count, $lastDayAppsCount);
		$today->apps = $todayApps;
		//Заявки из приложения

		$week = new \stdClass();

		$weekStartDate = new \DateTime('this week');
		$weekEndDate = new \DateTime('next sunday');

		$lastWeekStartDate = new \DateTime('last week');
		$lastWeekEndDate = new \DateTime('last sunday');

		$weekQuery = 'creationDate BETWEEN "' . $weekStartDate->format('Y-m-d') . ' 00:00:00" AND "' . $weekEndDate->format('Y-m-d') . ' 23:59:59"';
		$weekPaymentsQuery = 'payDate BETWEEN "' . $weekStartDate->format('Y-m-d') . ' 00:00:00" AND "' . $weekEndDate->format('Y-m-d') . ' 23:59:59"';
		$lastWeekQuery = 'creationDate BETWEEN "' . $lastWeekStartDate->format('Y-m-d') . ' 00:00:00" AND "' . $lastWeekEndDate->format('Y-m-d') . ' 23:59:59"';
		$lastWeekPaymentsQuery = 'payDate BETWEEN "' . $lastWeekStartDate->format('Y-m-d') . ' 00:00:00" AND "' . $lastWeekEndDate->format('Y-m-d') . ' 23:59:59"';

		//Заявки
		$weekRequests = new \stdClass();
		$weekRequests->count = (int)Requests::count($weekQuery. $webQuery);
		$lastWeekRequestsCount = (int)Requests::count($lastWeekQuery. $webQuery);
		$weekRequests->diff = Utils\Text::countDiff($weekRequests->count, $lastWeekRequestsCount);
		$week->requests = $weekRequests;
		//Заявки

		//Деньги
		$weekPayments = new \stdClass();
		$weekPayments->count = round(Payments::sum([
			'conditions' => $weekPaymentsQuery . " AND status = 'paid'",
			'column' => 'sum'
		]));
		$lastWeekPaymentsCount = round(Payments::sum([
			'conditions' => $lastWeekPaymentsQuery . " AND status = 'paid'",
			'column' => 'sum'
		]));
		$weekPayments->diff = Utils\Text::countDiff($weekPayments->count, $lastWeekPaymentsCount);
		$week->payments = $weekPayments;
		//Деньги

		//Заявки из приложений
		$weekApps = new \stdClass();
		$weekApps->count = (int)Requests::count($weekQuery  . $mobileQuery);
		$lastWeekAppsCount = (int)Requests::count($lastWeekQuery  . $mobileQuery);
		$weekApps->diff = Utils\Text::countDiff($weekApps->count, $lastWeekAppsCount);
		$week->apps = $weekApps;
		//Заявки из приложений

		$this->view->setVar('today', $today);
		$this->view->setVar('week', $week);

	}

	public function blogAction() {

		/*$this->view->disable();
		echo '<pre>';

		$db = mysqli_connect('localhost', 'putevki', 'Oz2000Pvv2013', 'putevki_ru');

		$items = $db->query('
			SELECT post.*, user.nickname as author, user.id as authorId FROM dwdlu_easyblog_post AS post 
			LEFT JOIN dwdlu_easyblog_users AS user ON user.id = post.createdBy
			WHERE post.published = 1
			ORDER BY post.created DESC
			LIMIT 1000
		')->fetch_all(MYSQLI_ASSOC);

		foreach($items as $item) {
			$post = new Posts();
			$post->fromJoomla($item);
			$post->create();
		}*/
	}

}

