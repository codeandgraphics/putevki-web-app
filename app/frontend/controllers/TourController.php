<?php

namespace Frontend\Controllers;

use Utils\Tourvisor as TourvisorUtils;

class TourController extends BaseController
{
	public function indexAction()
	{
	    $tourId = $this->dispatcher->getParam('id');
		$result = TourvisorUtils::getMethod('actualize', array(
			'tourid' => $tourId,
			'flights' => 1
		));

		$data = (!property_exists($result, 'error') || !$result->error) ? $result->data : false;

		if ($data) {
			$tour = $data->tour;
			$tour->id = $tourId;

			$date = \DateTime::createFromFormat('d.m.Y', $tour->flydate);
			$title = 'Путёвка ' . $tour->departurename . ' &mdash; ' . $tour->hotelregionname . ' (' . $date->format('d.m.Y') . ') на ';

			$tour->operatorname = str_replace('TezTour', 'TEZ TOUR', $tour->operatorname);

			$this->view->setVars([
				'tour' => $tour,
				'title' => $title,
				'page' => 'tour'
			]);
		} else {
			$this->dispatcher->forward(
				array(
					'controller' => 'error',
					'action' => 'error404',
				)
			);
		}
	}

	public function bookingAction($requestId)
	{
		$this->dispatcher->forward([
			'controller' => 'Backend\Controllers\Requests',
			'action' => 'booking',
			'params' => [$requestId, true]
		]);
	}

	public function agreementAction($requestId)
	{
		$this->dispatcher->forward([
			'controller' => 'Backend\Controllers\Requests',
			'action' => 'agreement',
			'params' => [$requestId, true]
		]);

	}

}
