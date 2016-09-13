<?php

namespace Backend\Controllers;

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
			$tour->from = Utils\Text::formatToDayMonth($request->flightToDepartureDate, 'Y-m-d');
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

	public function sendRequest($type, Requests $request)
	{
		$tour = new \stdClass();

		$tour->requestId = $request->id;
		$tour->name = $request->hotelRegion . ', ' . $request->hotelCountry;
		$tour->hotel = $request->hotelName;
		$tour->nights = Utils\Text::humanize('nights', $request->hotelNights);
		$tour->price = $request->price;
		$tour->meal = Utils\Text::humanize('meal', $request->hotelMeal);

		$tour->agreementLink = $this->frontendConfig->publicURL . 'tour/agreement/' . $request->id;
		$tour->bookingLink =  $this->frontendConfig->publicURL . 'tour/booking/' . $request->id;

		if($type == 'online')
		{
			$payment = Payments::findFirst('requestId = ' . $request->id);

			$tour->people = Utils\Text::humanize('people', $request->tourists->count());
			$tour->from = Utils\Text::formatToDayMonth($request->flightToDepartureDate, 'd.m.Y');

			$tour->payLink =  $this->frontendConfig->publicURL . 'pay/' . $payment->id;

			$params = [
				'tour'	=> $tour,
				'year'	=> date('Y'),
				'phone'	=> $this->frontendConfig->phone
			];

			$body = $this->generate('online', $params);

			$mailgun = new Mailgun();
			$mailgun->send($request->subjectEmail, 'Заказ тура на Путевки.ру', $body);
		}
		else
		{
			$tour->people = $request->hotelPlacement;
			$tour->from = $request->hotelDate;

			$params = [
				'tour'	=> $tour,
				'year'	=> date('Y'),
				'phone'	=> $this->frontendConfig->phone
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


	public function generate($template, $params)
	{
		$this->simpleView->setVars([
			'baseUrl'	=> $this->config->frontend->publicURL,
			'adminUrl'	=> $this->config->backend->publicURL,
			'assetsUrl'	=> $this->config->frontend->publicURL . 'assets_frontend_dev', //TODO: replace in production,
			'year'		=> date('Y')
		]);

		$this->simpleView->setVars($params);

		return $this->simpleView->render('email/' . $template);
	}
}