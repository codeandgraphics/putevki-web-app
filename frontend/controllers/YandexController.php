<?php

use Phalcon\Http\Response			as Response,
	Models\Tourvisor				as Tourvisor;

class YandexController extends ControllerFrontend
{
	public function indexAction()
	{
		$yandex = new \Utils\Yandex();
		$tourvisor = new \Utils\Tourvisor();

		if(isset($_POST['offer'])){

			$hmac = $_POST['hash'];
			$offer = $_POST['offer'];
			$label = $_POST['label'];

			if(!$yandex->checkHMAC($offer, $hmac)){
				die('Неправильный формат запроса. <br/><a href="http://putevki.ru">Путевки.ру</a>');
			}
		}else{
			$offer = file_get_contents(__DIR__ . '/actual.json');
			$label = false;
		}

		$actual = json_decode($offer);
		//$unactual = json_decode(file_get_contents('json/unactual_kid.json'));

		$tour = $yandex->parseTour($actual);

		print_r($tour);
		//$tourvisor->fillYandexTour($tour);

		$sig = Helper::hash($tour->final_price .'|'. $tour->hotel->id .'|'. $tour->start_time);


		$data = (!$result->error) ? $result->data : false ;
		
		if($data)
		{
			$tour = $data->tour;
			$tour->id = $tourId;
			$tour->flights = $data->flights->flight;

			foreach($tour->flights as $flight)
			{
				$toDateAdd = false;

				$flight->forward->timefrom = str_replace('.', ':', $flight->forward->timefrom);
				$toDateDeparture = DateTime::createFromFormat('H:i d.m.Y', $flight->forward->timefrom . " " . $flight->forward->flydate);

				$toTimeArrival = str_replace('.', ':', $flight->forward->timeto);
				if(strpos($toTimeArrival, '+') !== false)
				{
					list($toTimeArrival, $toDateAdd) = explode('+', $toTimeArrival);
				}
				$toDateArrival = DateTime::createFromFormat('H:i d.m.Y', $toTimeArrival . " " . $flight->forward->flydate);
				if($toDateAdd) $toDateArrival->modify('+'.$toDateAdd.' days');


				$fromDateAdd = false;

				$flight->backward->timefrom = str_replace('.', ':', $flight->backward->timefrom);
				$fromDateDeparture = DateTime::createFromFormat('H:i d.m.Y', $flight->backward->timefrom . " " . $flight->backward->flydate);

				$fromTimeArrival = str_replace('.', ':', $flight->backward->timeto);
				if(strpos($fromTimeArrival, '+') !== false)
				{
					list($fromTimeArrival, $fromDateAdd) = explode('+',$fromTimeArrival);
				}
				$fromDateArrival = DateTime::createFromFormat('H:i d.m.Y', $fromTimeArrival . " " . $flight->backward->flydate);
				if($fromDateAdd) $fromDateArrival->modify('+'.$fromDateAdd.' days');

				$flight->forward->datefrom = $toDateDeparture;
				$flight->forward->dateto = $toDateArrival;

				$flight->backward->datefrom = $fromDateDeparture;
				$flight->backward->dateto = $fromDateArrival;
			}

			$date = \DateTime::createFromFormat('d.m.Y',$tour->flydate);
			$title =  "Тур " . $tour->departurename . " &mdash; " . $tour->hotelregionname . " (" . $date->format('d.m.Y') . ") на ";

			$this->view->setVars([
				'tour'		=> $tour,
				'title'		=> $title,
				'page'		=> 'tour'
			]);
		}
		else
		{
			$this->view->disable();
			$response = new \Phalcon\Http\Response();
			$response->setStatusCode(404, "Not found");
			$response->setJsonContent([ 'error' => '404' ]);
			$response->send();
		}
	}

	public function bookingAction($requestId)
	{
		$this->dispatcher->forward([
			'controller'	=> 'Backend\Controllers\Requests',
			'action'		=> 'booking',
			'params'		=> [ $requestId, true ]
		]);
	}

	public function agreementAction($requestId)
	{
		$this->dispatcher->forward([
			'controller'	=> 'Backend\Controllers\Requests',
			'action'		=> 'agreement',
			'params'		=> [ $requestId, true ]
		]);

	}
	
}
