<?php

namespace Backend\Controllers;

use Models\Branches;
use Phalcon\Http\Response	as Response,
	Backend\Models\Requests as Requests,
	Backend\Models\Payments	as Payments,
	Backend\Models\Users	as Users,
	Utils\Email\Mailgun		as Mailgun,
	Models\Tourvisor,
	Utils;

class EmailController extends ControllerBase
{
	public function onlineAction()
	{
		$this->view->disable();

		$request = Requests::findFirst(33);

		$this->sendRequest('online', $request);
	}

	public function sendPassword($email, $password)
	{
		$params = [
			'email'		=> $email,
			'password'	=> $password
		];

		$body = $this->generate('passwordNotification', $params);

		$mailgun = new Mailgun();
		$mailgun->send($email, 'Регистрация в панели управления Путевки.ру', $body);
	}

	public function sendManagerNotification(Requests $request)
	{
		if($request->manager)
		{
			$tour = new \stdClass();

			$tour->name = $request->hotelRegion . ', ' . $request->hotelCountry;
			$tour->hotel = $request->hotelName;
			$tour->people = Utils\Text::humanize('people', $request->tourists->count());
			$tour->from = Utils\Text::formatToDayMonth($request->hotelDate, 'Y-m-d');
			$tour->nights = Utils\Text::humanize('nights', $request->hotelNights);
			$tour->price = $request->price;
			$tour->meal = Utils\Text::humanize('meal', $request->hotelMeal);
			$tour->manager = $request->manager;
			$tour->orderName = $request->subjectName . ' ' . $request->subjectSurname;
			$tour->phone = $request->subjectPhone;
			$tour->email = $request->subjectEmail;
			$tour->id = $request->id;

			$params = [
				'tour'	=> $tour
			];

			$body = $this->generate('managerNotification', $params);

			$mailgun = new Mailgun();
			$mailgun->send($request->manager->email, 'Новая заявка на тур', $body);
		}
	}

	public function sendBranchNotification(Requests $request)
	{
		if($request->branch_id)
		{
			$manager = $request->branch->manager;

			$tour = new \stdClass();

			$tour->name = $request->hotelRegion . ', ' . $request->hotelCountry;
			$tour->hotel = $request->hotelName;
			$tour->people = $request->hotelPlacement;
			$tour->departure = $request->departure->name;
			$tour->departureFrom = $request->departure->name_from;
			$tour->from = Utils\Text::formatToDayMonth($request->hotelDate, 'Y-m-d');
			$tour->nights = Utils\Text::humanize('nights', $request->hotelNights);
			$tour->price = $request->price;
			$tour->meal = Utils\Text::humanize('meal', $request->hotelMeal);
			$tour->manager = $manager;
			$tour->orderName = $request->subjectName . ' ' . $request->subjectSurname;
			$tour->phone = $request->subjectPhone;
			$tour->email = $request->subjectEmail;
			$tour->id = $request->id;

			$params = [
				'tour'	=> $tour
			];

			$body = $this->generate('managerNotification', $params);

			$mailgun = new Mailgun();
			$mailgun->send($manager->email, 'Новая заявка на тур', $body, $request->branch->additionalEmails);
		}
	}

	public function sendRequest($type, Requests $request)
	{
		$tour = new \stdClass();

		$tour->requestId = $request->id;
		$tour->name = $request->hotelRegion . ', ' . $request->hotelCountry;
		$tour->hotel = $request->hotelName;
		$tour->from = Utils\Text::formatToDayMonth($request->hotelDate, 'Y-m-d');
		$tour->people = $request->hotelPlacement;
		$tour->departure = $request->departure->name;
		$tour->departureFrom = $request->departure->name_from;
		$tour->nights = Utils\Text::humanize('nights', $request->hotelNights);
		$tour->price = $request->price;
		$tour->meal = Utils\Text::humanize('meal', $request->hotelMeal);

		$tour->agreementLink = $this->config->frontend->publicURL . 'tour/agreement/' . $request->id;
		$tour->bookingLink =  $this->config->frontend->publicURL . 'tour/booking/' . $request->id;

		$tour->flight = new \stdClass();

		if($request->flightToNumber)
		{
			$tour->flight->to->number = $request->flightToNumber;
			$tour->flight->to->plane = $request->flightToPlane;
			$tour->flight->to->carrier = $request->flightToCarrier;
			$tour->flight->to->departure->date = Utils\Text::formatToDayMonth($request->flightToDepartureDate, 'Y-m-d');
			$tour->flight->to->departure->time = $request->flightToDepartureTime;
			$tour->flight->to->departure->terminal = $request->flightToDepartureTerminal;
			$tour->flight->to->arrival->date = Utils\Text::formatToDayMonth($request->flightToArrivalDate, 'Y-m-d');
			$tour->flight->to->arrival->time = $request->flightToArrivalTime;
			$tour->flight->to->arrival->terminal = $request->flightToArrivalTerminal;

			$tour->flight->from->number = $request->flightFromNumber;
			$tour->flight->from->plane = $request->flightFromPlane;
			$tour->flight->from->carrier = $request->flightFromCarrier;
			$tour->flight->from->departure->date = Utils\Text::formatToDayMonth($request->flightFromDepartureDate, 'Y-m-d');
			$tour->flight->from->departure->time = $request->flightFromDepartureTime;
			$tour->flight->from->departure->terminal = $request->flightFromDepartureTerminal;
			$tour->flight->from->arrival->date = Utils\Text::formatToDayMonth($request->flightFromArrivalDate, 'Y-m-d');
			$tour->flight->from->arrival->time = $request->flightFromArrivalTime;
			$tour->flight->from->arrival->terminal = $request->flightFromArrivalTerminal;
		}
		else
		{
			$tour->flight = false;
		}


		if($type == 'online')
		{
			$payment = Payments::findFirst('requestId = ' . $request->id);
			$tour->payLink =  $this->config->frontend->publicURL . 'pay/' . $payment->id;

			$params = [
				'tour'	=> $tour,
				'year'	=> date('Y'),
				'phone'	=> $this->config->frontend->phone
			];

			$body = $this->generate('online', $params);

			$mailgun = new Mailgun();
			$mailgun->send($request->subjectEmail, 'Заказ тура на Путевки.ру', $body);
		}
		else
		{
			$params = [
				'tour'	=> $tour,
				'year'	=> date('Y'),
				'phone'	=> $this->config->frontend->phone
			];

			$body = $this->generate('request', $params);

			$mailgun = new Mailgun();
			$mailgun->send($request->subjectEmail, 'Заявка на тур на Путевки.ру', $body);
		}
	}

	public function sendFindTour($data)
	{
		$params = [
			'find'	=> $data,
			'year'	=> date('Y')
		];

		$body = $this->generate('findTour', $params);

		$mailgun = new Mailgun();
		$mailgun->send($this->config->backend->requestEmail, 'Заявка на подбор тура на Путевки.ру', $body);
	}

	public function sendTourHelp($phone, $queries)
	{
		$params = [
			'phone'     => $phone,
			'queries'   => $queries,
			'year'      => date('Y')
		];

		$body = $this->generate('tourHelp', $params);

		$mailgun = new Mailgun();
		$mailgun->send($this->config->backend->requestEmail, 'Заявка на звонок на Путевки.ру', $body);
	}

	public function generate($template, $params)
	{
		$this->simpleView->setVars([
			'baseUrl'	=> $this->config->frontend->publicURL,
			'adminUrl'	=> $this->config->backend->publicURL,
			'assetsUrl'	=> $this->config->frontend->publicURL . 'assets',
			'year'		=> date('Y')
		]);

		$this->simpleView->setVars($params);

		return $this->simpleView->render('email/' . $template);
	}
}