<?php

namespace Backend\Controllers;

use Backend\Models\Requests;
use Backend\Models\Payments;
use Backend\Models\Tourists;
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

		//Заявки
		$todayRequests = new \stdClass();
		$todayRequests->count = (int)Requests::count($todayQuery);
		$lastDayRequestsCount = (int)Requests::count($lastDayQuery);
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


		//Туристы
		$todayTourists = new \stdClass();
		$todayTourists->count = (int)Tourists::count($todayQuery);
		$lastDayTouristsCount = (int)Tourists::count($lastDayQuery);
		$todayTourists->diff = Utils\Text::countDiff($todayTourists->count, $lastDayTouristsCount);
		$today->tourists = $todayTourists;
		//Туристы


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
		$weekRequests->count = (int)Requests::count($weekQuery);
		$lastWeekRequestsCount = (int)Requests::count($lastWeekQuery);
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


		//Туристы
		$weekTourists = new \stdClass();
		$weekTourists->count = (int)Tourists::count($weekQuery);
		$lastWeekTouristsCount = (int)Tourists::count($lastWeekQuery);
		$weekTourists->diff = Utils\Text::countDiff($weekTourists->count, $lastWeekTouristsCount);
		$week->tourists = $weekTourists;
		//Туристы

		$this->view->setVar('today', $today);
		$this->view->setVar('week', $week);

	}

}

