<?php

class TourController extends ControllerFrontend
{
	public function indexAction($tourId)
	{
		$result = Utils\Tourvisor::getMethod('actualize', array(
			'tourid'		=> $tourId,
			'flights'		=> 1
		));

		$detailData = Utils\Tourvisor::getMethod('actdetail', array(
			'tourid'		=> $tourId
		));

		$data = (!$result->error) ? $result->data : false ;

		if($data)
		{
			$tour = $data->tour;
			$tour->id = $tourId;

			if(property_exists($detailData, 'tourinfo'))
			{
				if(property_exists($detailData->tourinfo, 'flags'))
				{
					$tour->flags = $detailData->tourinfo->flags;
				}

				if(property_exists($detailData->tourinfo, 'addpayments'))
				{
					$tour->addpayments = $detailData->tourinfo->addpayments;
				}
			}

			if(count($detailData->flights) > 0)
			{
				$tour->flights = $detailData->flights;

				foreach($tour->flights as $flight)
				{

					if(count($flight->forward) > 0 && count($flight->backward) > 0)
					{
						$toDateAdd = false;

						$flight->forward[0]->departure->time = str_replace('.', ':', $flight->forward[0]->departure->time);
						$toDateDeparture = DateTime::createFromFormat('H:i d.m.Y', $flight->forward[0]->departure->time . ' ' . $flight->dateforward);


						$toTimeArrival = str_replace('.', ':', $flight->forward[0]->arrival->time);
						if(strpos($toTimeArrival, '+') !== false)
						{
							list($toTimeArrival, $toDateAdd) = explode('+', $toTimeArrival);
						}
						$toDateArrival = DateTime::createFromFormat('H:i d.m.Y', $toTimeArrival . ' ' . $flight->dateforward);
						if($toDateAdd) $toDateArrival->modify('+'.$toDateAdd.' days');

						$fromDateAdd = false;

						$flight->backward[0]->departure->time = str_replace('.', ':', $flight->backward[0]->departure->time);
						$fromDateDeparture = DateTime::createFromFormat('H:i d.m.Y', $flight->backward[0]->departure->time . ' ' . $flight->datebackward);

						$fromTimeArrival = str_replace('.', ':', $flight->backward[0]->arrival->time);
						if(strpos($fromTimeArrival, '+') !== false)
						{
							list($fromTimeArrival, $fromDateAdd) = explode('+',$fromTimeArrival);
						}
						$fromDateArrival = DateTime::createFromFormat('H:i d.m.Y', $fromTimeArrival . " " . $flight->datebackward);
						if($fromDateAdd) $fromDateArrival->modify('+'.$fromDateAdd.' days');

						$flight->forward[0]->datefrom = $toDateDeparture;
						$flight->forward[0]->dateto = $toDateArrival;

						$flight->backward[0]->datefrom = $fromDateDeparture;
						$flight->backward[0]->dateto = $fromDateArrival;

						if(
							!$flight->forward[0]->datefrom ||
							!$flight->forward[0]->dateto ||
							!$flight->backward[0]->datefrom ||
							!$flight->backward[0]->dateto)
						{
							unset($flight->forward, $flight->backward);
						}
					}
					else
					{
						unset($flight->forward, $flight->backward);
					}
				}
			}

			$date = \DateTime::createFromFormat('d.m.Y',$tour->flydate);
			$title =  'Тур ' . $tour->departurename . ' &mdash; ' . $tour->hotelregionname . ' (' . $date->format('d.m.Y') . ') на ';

			$this->view->setVars([
				'tour'		=> $tour,
				'title'		=> $title,
				'page'		=> 'tour'
			]);
		}
		else
		{
			$this->dispatcher->forward(
				array(
					'controller' => 'error',
					'action'     => 'error404',
				)
			);
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
