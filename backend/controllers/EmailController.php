<?php

namespace Backend\Controllers;

use Backend\Models\Requests;
use Backend\Models\Payments;
use Utils\Email\Mailgun;
use Utils;

class EmailController extends ControllerBase
{
	public function testAction($type)
	{
		$this->view->disable();

		$requestId = 30;

		switch ($type) {
			case 'online':
				$this->sendRequest('online', $requestId, true);
				break;
			case 'request':
				$this->sendRequest('request', $requestId, true);
				break;
			case 'admin':
				$this->sendAdminNotification($requestId, true);
				break;
			case 'manager':
				$this->sendManagerNotification($requestId, true);
				break;
			case 'branch':
				$this->sendBranchNotification($requestId, true);
				break;
			case 'findTour':
				$this->sendFindTour((object) ['name'=>'test', 'from'=>'asd'], true);
				break;
			case 'tourHelp':
				$this->sendTourHelp('phone', (object) ['name'=>'test', 'from'=>'asd'], true);
				break;
			case 'password':
				$this->sendPassword('test@test.com', 'asd', true);
				break;
			default:
				break;
		}
	}

	public function sendPassword($email, $password, $isTest = false)
	{
		$params = [
			'email' => $email,
			'password' => $password
		];

		$body = $this->generate('passwordNotification', $params);

		if($isTest) {
			echo $body;
		} else {
			$mailgun = new Mailgun();
			$mailgun->send($email, 'Регистрация в панели управления Путевки.ру', $body);
		}
	}

	public function sendAdminNotification(int $requestId, $isTest = false)
	{
		$tour = new \stdClass();

		$request = Requests::findFirstById($requestId);

		if(!$request) {
			return false;
		}

		$hotel = $request->getHotel();

		$tour->name = $hotel->region . ', ' . $hotel->country;
		$tour->hotel = $hotel->name;
		$tour->people = $hotel->placement;
		$tour->from = Utils\Text::formatToDayMonth($hotel->date, 'd.m.Y');
		$tour->nights = Utils\Text::humanize('nights', $hotel->nights);
		$tour->meal = Utils\Text::humanize('meal', $hotel->meal);
		$tour->price = $request->price;
		$tour->manager = $request->manager;
		$tour->orderName = $request->subjectName . ' ' . $request->subjectSurname;
		$tour->phone = $request->subjectPhone;
		$tour->email = $request->subjectEmail;
		$tour->id = $request->id;

		$params = [
			'tour' => $tour
		];

		$body = $this->generate('managerNotification', $params);

		if($isTest) {
			echo $body;
		} else {
			$mailgun = new Mailgun();
			$mailgun->send($this->config->defaults->requestsEmail, 'Новая заявка на тур', $body);
		}
	}

	public function sendManagerNotification(int $requestId, $isTest = false)
	{
		$request = Requests::findFirstById($requestId);

		if(!$request) {
			return false;
		}

		if ($request->manager) {
			$tour = new \stdClass();

			$hotel = $request->getHotel();

			$tour->name = $hotel->region . ', ' . $hotel->country;
			$tour->hotel = $hotel->name;
			$tour->people = $hotel->placement;
			$tour->from = Utils\Text::formatToDayMonth($hotel->date, 'd.m.Y');
			$tour->nights = Utils\Text::humanize('nights', $hotel->nights);
			$tour->meal = Utils\Text::humanize('meal', $hotel->meal);
			$tour->price = $request->price;
			$tour->manager = $request->manager;
			$tour->orderName = $request->subjectName . ' ' . $request->subjectSurname;
			$tour->phone = $request->subjectPhone;
			$tour->email = $request->subjectEmail;
			$tour->id = $request->id;

			$params = [
				'tour' => $tour
			];

			$body = $this->generate('managerNotification', $params);

			if($isTest) {
				echo $body;
			} else {
				$mailgun = new Mailgun();
				$mailgun->send($this->config->defaults->requestsEmail, 'Новая заявка на тур', $body);
			}
		}
	}

	public function sendBranchNotification(int $requestId, $isTest = false)
	{
		$request = Requests::findFirstById($requestId);

		if(!$request) {
			return false;
		}

		if ($request->branch_id) {
			$manager = $request->branch->manager;

			$tour = new \stdClass();


			$hotel = $request->getHotel();

			$tour->name = $hotel->region . ', ' . $hotel->country;
			$tour->hotel = $hotel->name;
			$tour->people = $hotel->placement;
			$tour->from = Utils\Text::formatToDayMonth($hotel->date, 'd.m.Y');
			$tour->nights = Utils\Text::humanize('nights', $hotel->nights);
			$tour->meal = Utils\Text::humanize('meal', $hotel->meal);

			$tour->departure = $request->departure->name;
			$tour->departureFrom = $request->departure->name_from;

			$tour->price = $request->price;
			$tour->manager = $manager;
			$tour->orderName = $request->subjectName . ' ' . $request->subjectSurname;
			$tour->phone = $request->subjectPhone;
			$tour->email = $request->subjectEmail;
			$tour->id = $request->id;

			$params = [
				'tour' => $tour
			];

			$body = $this->generate('managerNotification', $params);

			if($isTest) {
				echo $body;
			} else {
				$mailgun = new Mailgun();
				$mailgun->send($manager->email, 'Новая заявка на тур', $body, $request->branch->additionalEmails);
			}
		}
	}

	public function sendRequest($type, int $requestId, $isTest = false)
	{
		$request = Requests::findFirstById($requestId);

		if(!$request) {
			return false;
		}
		$tour = new \stdClass();


		$hotel = $request->getHotel();

		$tour->name = $hotel->region . ', ' . $hotel->country;
		$tour->hotel = $hotel->name;
		$tour->people = $hotel->placement;
		$tour->from = Utils\Text::formatToDayMonth($hotel->date, 'd.m.Y');
		$tour->nights = Utils\Text::humanize('nights', $hotel->nights);
		$tour->meal = Utils\Text::humanize('meal', $hotel->meal);

		$tour->requestId = $request->id;
		$tour->departure = $request->departure->name;
		$tour->departureFrom = $request->departure->name_from;
		$tour->price = $request->price;

		$tour->agreementLink = $this->url->get('tour/agreement/' . $request->id);
		$tour->bookingLink = $this->url->get('tour/booking/' . $request->id);

		$tour->flights = new \stdClass();

		if ($request->hasFlights()) {
			$tour->flights->to = $request->getFlights('To');
			$tour->flights->from = $request->getFlights('From');
		} else {
			$tour->flights = false;
		}

		$template = 'request';
		$subject = 'Заявка на тур на Путевки.ру';

		if ($type === 'online') {
			$payment = Payments::findFirst('requestId = ' . $request->id);
			$tour->payLink = $this->url->get('pay/' . $payment->id);

			$template = 'online';
			$subject = 'Заказ тура на Путевки.ру';
		}

		$params = [
			'tour' => $tour,
			'year' => date('Y'),
			'phone' => $this->config->defaults->phone
		];

		$body = $this->generate($template, $params);

		if($isTest) {
			echo $body;
		} else {
			$mailgun = new Mailgun();
			$mailgun->send($request->subjectEmail, $subject, $body);
		}
	}

	public function sendFindTour($data, $isTest = false)
	{
		$params = [
			'find' => $data,
			'year' => date('Y')
		];

		$body = $this->generate('findTour', $params);

		if($isTest) {
			echo $body;
		} else {
			$mailgun = new Mailgun();
			$mailgun->send($this->config->defaults->requestsEmail, 'Заявка на подбор тура на Путевки.ру', $body);
		}
	}

	public function sendTourHelp($phone, $queries, $isTest = false)
	{
		$params = [
			'phone' => $phone,
			'queries' => $queries,
			'year' => date('Y')
		];

		$body = $this->generate('tourHelp', $params);

		if($isTest) {
			echo $body;
		} else {
			$mailgun = new Mailgun();
			$mailgun->send($this->config->defaults->requestsEmail, 'Заявка на звонок на Путевки.ру', $body);
		}
	}

	public function generate($template, $params)
	{
		$this->simpleView->setVars([
			'baseUrl' => $this->url->get(),
			'adminUrl' => $this->backendUrl->get(),
			'assetsUrl' => $this->url->getStatic(),
			'year' => date('Y')
		]);

		$this->simpleView->setVars($params);

		return $this->simpleView->render('email/' . $template);
	}
}