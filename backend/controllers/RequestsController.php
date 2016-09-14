<?php

namespace Backend\Controllers;

use Models\Branches;
use Phalcon\Http\Response			as Response,
	Phalcon\Forms\Form,
	Phalcon\Forms\Element\Text,
	Phalcon\Forms\Element\Select,
	Phalcon\Mvc\View				as View,
	Phalcon\Paginator\Adapter\Model as PaginatorModel,
	Backend\Models\Users			as Users,
	Backend\Models\Requests			as Requests,
	Backend\Models\RequestStatuses	as RequestStatuses,
	Backend\Models\RequestTourists	as RequestTourists,
	Backend\Models\Payments			as Payments,
	Utils\Text						as TextUtils,
	Models\Tourvisor;
use Phalcon\Mvc\Model\Validator\Email;

class RequestsController extends ControllerBase
{

	public function indexAction()
	{
		$query = [
			'order'	=> 'creationDate DESC',
		];

		if($this->user->role == Users::ROLE_MANAGER)
		{
			$query[] = 'branch_id = ' . $this->user->branch_id;
		}

		$requests = Requests::find($query);

		$paginator = new PaginatorModel(
			array(
				"data"  	=> $requests,
				"limit" 	=> 30,
				"page"  	=> $this->request->get('page')
			)
		);

		$this->view->page = $paginator->getPaginate();

	}

	public function addAction()
	{
		$form = new Form();

		$form->add(new Text('price'));
		$form->add(new Text('subjectSurname'));
		$form->add(new Text('subjectName'));
		$form->add(new Text('subjectPatronymic'));
		$form->add(new Text('subjectAddress'));
		$form->add(new Text('subjectPhone'));
		$form->add(new Text('subjectEmail'));

		$form->add(new Text('hotelName'));
		$form->add(new Text('hotelCountry'));
		$form->add(new Text('hotelRegion'));
		$form->add(new Text('hotelDate'));
		$form->add(new Text('hotelNights'));
		$form->add(new Text('hotelPlacement'));
		$form->add(new Text('hotelMeal'));
		$form->add(new Text('hotelRoom'));

		$departures = Tourvisor\Departures::find(['order'=>'name']);
		$form->add(new Select('departureId', $departures, ['using' => ['id', 'name']]));

		$tourOperators = Tourvisor\Operators::find();
		$form->add(new Select('tourOperatorId', $tourOperators, ['using' => ['id', 'name']]));

		$requestStatuses = RequestStatuses::find();
		$form->add(new Select('requestStatusId', $requestStatuses, ['using' => ['id', 'name']]));

		foreach(['To', 'From'] as $direction){
			$form->add(new Text('flight'.$direction.'Number'));
			$form->add(new Text('flight'.$direction.'DepartureDate'));
			$form->add(new Text('flight'.$direction.'DepartureTime'));
			$form->add(new Text('flight'.$direction.'DepartureTerminal'));
			$form->add(new Text('flight'.$direction.'ArrivalDate'));
			$form->add(new Text('flight'.$direction.'ArrivalTime'));
			$form->add(new Text('flight'.$direction.'ArrivalTerminal'));
			$form->add(new Text('flight'.$direction.'Carrier'));
			$form->add(new Text('flight'.$direction.'Plane'));
			$form->add(new Text('flight'.$direction.'Class'));
		}

		$tourists = [];

		if($this->request->isPost())
		{
			$touristIds = $this->request->getPost('tourists');

			$request = new Requests();
			$form->bind($_POST, $request);

			if($form->isValid())
			{
				if($this->user->role == Users::ROLE_MANAGER)
				{
					$request->branch_id = $this->user->branch_id;
				}

				if($request->save())
				{

					if($touristIds){

						$tourists = $this->modelsManager->createBuilder()
							->from('Models\Tourists')
							->inWhere('id',$touristIds)
							->getQuery()
							->execute();

						foreach($tourists as $tourist)
						{
							$requestTourist = new RequestTourists();
							$requestTourist->requestId = $request->id;
							$requestTourist->touristId = $tourist->id;

							$requestTourist->save();
						}
					}

					$this->flashSession->success('Заявка успешно добавлена!');
					return $this->response->redirect("requests/edit/" . $request->id);
				}
				else
				{
					foreach($request->getMessages() as $message)
					{
						$this->flashSession->error($message);
					}
				}
			}

		}

		if($this->user->role === Users::ROLE_ADMIN)
		{
			$branches = Branches::find('active = 1');
			$this->view->setVar('branches', $branches);

			$form->add(new Select('branch_id', $branches, ['using' => ['id', 'name']]));
		}

		$this->view->setVars([
			'tourists'	=> $tourists,
			'form'		=> $form
		]);
	}

	public function editAction($requestId)
	{
		if($this->user->role === Users::ROLE_MANAGER)
		{
			$request = Requests::findFirst('id = ' . $requestId . ' AND branch_id = ' . $this->user->branch_id);
		}
		else
		{
			$request = Requests::findFirst($requestId);
		}

		if(!$request)
		{
			return $this->error404();
		}

		$oldBranch = $request->branch_id;

		$form = new Form($request);

		$form->add(new Text('price'));
		$form->add(new Text('subjectSurname'));
		$form->add(new Text('subjectName'));
		$form->add(new Text('subjectPatronymic'));
		$form->add(new Text('subjectAddress'));
		$form->add(new Text('subjectPhone'));
		$form->add(new Text('subjectEmail'));

		$form->add(new Text('hotelName'));
		$form->add(new Text('hotelCountry'));
		$form->add(new Text('hotelRegion'));
		$form->add(new Text('hotelDate'));
		$form->add(new Text('hotelNights'));
		$form->add(new Text('hotelPlacement'));
		$form->add(new Text('hotelMeal'));
		$form->add(new Text('hotelRoom'));

		$departures = Tourvisor\Departures::find(['order'=>'name']);
		$form->add(new Select('departureId', $departures, ['using' => ['id', 'name']]));

		$tourOperators = Tourvisor\Operators::find();
		$form->add(new Select('tourOperatorId', $tourOperators, ['using' => ['id', 'name']]));

		$requestStatuses = RequestStatuses::find();
		$form->add(new Select('requestStatusId', $requestStatuses, ['using' => ['id', 'name']]));

		foreach(['To', 'From'] as $direction){
			$form->add(new Text('flight'.$direction.'Number'));
			$form->add(new Text('flight'.$direction.'DepartureDate'));
			$form->add(new Text('flight'.$direction.'DepartureTime'));
			$form->add(new Text('flight'.$direction.'DepartureTerminal'));
			$form->add(new Text('flight'.$direction.'ArrivalDate'));
			$form->add(new Text('flight'.$direction.'ArrivalTime'));
			$form->add(new Text('flight'.$direction.'ArrivalTerminal'));
			$form->add(new Text('flight'.$direction.'Carrier'));
			$form->add(new Text('flight'.$direction.'Plane'));
			$form->add(new Text('flight'.$direction.'Class'));
		}

		$tourists = [];

		foreach($request->tourists as $tourist)
		{
			$tourists[] = $tourist->tourist;
		}

		if($this->user->role === Users::ROLE_ADMIN)
		{
			$dbBranches = Branches::find('active = 1');

			$branches[0] = 'Не выбрано';

			foreach($dbBranches as $branch)
			{
				$branches[$branch->id] = $branch->name . ' ' . $branch->city->name . ' (' . $branch->manager->name .')';
			}

			$form->add(new Select('branch_id', $branches));
		}

		if($this->request->isPost()) {

			$touristIds = $this->request->getPost('tourists');

			if ($touristIds) {

				$phql = 'DELETE FROM Backend\Models\RequestTourists WHERE requestId = :requestId:';
				$this->modelsManager->executeQuery($phql, [
						'requestId' => $request->id
				]);

				$tourists = $this->modelsManager->createBuilder()
						->from('Backend\Models\Tourists')
						->inWhere('id', $touristIds)
						->getQuery()
						->execute();

				foreach ($tourists as $tourist) {
					$requestTourist = new RequestTourists();
					$requestTourist->requestId = $request->id;
					$requestTourist->touristId = $tourist->id;

					$requestTourist->save();
				}
			}
			$form->bind($_POST, $request);

			if($form->isValid())
			{
				if($request->save())
				{
					if((int) $request->branch_id !== (int) $oldBranch)
					{
						$email = new EmailController();
						$email->sendManagerNotification($request);
					}

					$this->flashSession->success('Заявка успешно сохранена!');
					return $this->response->redirect('requests/edit/' . $request->id);
				}
				else
				{
					foreach($request->getMessages() as $message)
					{
						$this->flashSession->error($message);
					}
				}
			}
		}

		$this->view->setVar('req', $request);
		$this->view->setVar('tourists', $tourists);

		$this->view->setVar('form', $form);
	}

	public function bookingAction($requestId, $download = null)
	{
		$this->view->disable();

		$pdf = new \mPDF('BLANK', 'A4', 8, 'utf-8', 8, 8, 20, 20, 0, 0);

		$request = Requests::findFirst($requestId);

		$this->simpleView->setVar('req', $request);
		$this->simpleView->setVar('assetsUrl', $this->config->frontend->publicURL . 'assets');
		$html = $this->simpleView->render('requests/pdf/booking');
		$css = file_get_contents(__DIR__ . '/../views/requests/pdf/style.css');

		$header = $this->simpleView->render('requests/pdf/header');
		$footer = $this->simpleView->render('requests/pdf/footer');

		$pdf->WriteHTML($css, 1);
		$pdf->SetHTMLHeader($header);
		$pdf->SetHTMLFooter($footer);
		$pdf->WriteHTML($html, 2);

		if($download)
			$pdf->Output('booking-'.$request->getNumber().'.pdf', "D");
		else
			$pdf->Output('booking-'.$request->getNumber().'.pdf', "I");

	}

	public function agreementAction($requestId, $download = null)
	{
		$this->view->disable();

		$pdf = new \mPDF('BLANK', 'A4', 8, 'utf-8', 8, 8, 20, 20, 0, 0);

		$request = Requests::findFirst($requestId);

		$this->simpleView->setVar('req', $request);
		$this->simpleView->setVar('assetsUrl', $this->config->frontend->publicURL . 'assets');
		$html = $this->simpleView->render('requests/pdf/agreement');
		$css = file_get_contents(__DIR__ . '/../views/requests/pdf/style.css');

		$header = $this->simpleView->render('requests/pdf/header');
		$footer = $this->simpleView->render('requests/pdf/footer');

		$pdf->WriteHTML($css, 1);
		$pdf->SetHTMLHeader($header);
		$pdf->SetHTMLFooter($footer);
		$pdf->WriteHTML($html, 2);

		if($download)
			$pdf->Output('agreement-'.$request->getNumber().'.pdf', "D");
		else
			$pdf->Output('agreement-'.$request->getNumber().'.pdf', "I");
	}


	public function ajaxHotelsAction()
	{
		if($this->request->isGet())
		{
			$search = mb_strtoupper($this->request->get('term'));

			$query = "SELECT * FROM \Models\Tourvisor\Hotels
						WHERE \Models\Tourvisor\Hotels.name LIKE :search:
						LIMIT 20";
			$hotels =$this->modelsManager->executeQuery($query, ["search" => '%'.$search.'%']);

			$response = [];

			foreach($hotels as $hotel)
			{
				$response[] = $hotel->format();
			}

			echo json_encode($response);
			$this->view->disable();
		}
		else
		{
			$this->response->redirect('404');
		}

	}
}