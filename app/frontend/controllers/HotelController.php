<?php

namespace Frontend\Controllers;

use Frontend\Models\Params;
use Models\Origin;
use Models\SearchQuery;
use Models\Tourvisor;
use Utils\Tourvisor as TourvisorUtils;
use Utils\Text as TextUtils;

class HotelController extends BaseController
{
	public function indexAction()
	{
		$id = $this->dispatcher->getParam('id', 'int');

        $result = TourvisorUtils::getMethod('hotel', array(
			'hotelcode' => $id,
			'imgwidth' => 400,
			'imgheight' => 260
		));

		$hotel = $result->data->hotel;

        $dbHotel = Tourvisor\Hotels::findFirst('id="' . $id . '"');

		$dbTypes = new \stdClass();

		$dbTypes->active = $dbHotel->active;
		$dbTypes->relax = $dbHotel->relax;
		$dbTypes->family = $dbHotel->family;
		$dbTypes->health = $dbHotel->health;
		$dbTypes->city = $dbHotel->city;
		$dbTypes->beach = $dbHotel->beach;
		$dbTypes->deluxe = $dbHotel->deluxe;

		$types = [];

		foreach ((array)$dbTypes as $key => $value) {

			if ((int) $value === 1) {
				$types[$key] = TextUtils::humanize('types', $key);
			}
		}

		$operator = $this->request->get('operator');

		$hotel->types = $types;

		if (!array_key_exists('deluxe', $hotel->types)) {
			$hotel->types['deluxe'] = false;
		}

		$hotel->db = $dbHotel;

		$hotel->humanizeRating = TextUtils::humanize('rating', $hotel->rating);

		$hotel->coord1 = str_replace(',', '.', $hotel->coord1);
		$hotel->coord2 = str_replace(',', '.', $hotel->coord2);

        $departures = Tourvisor\Departures::find([
            'id NOT IN (:moscowId:, :spbId:, :noId:)',
            'bind' => [
                'moscowId'	=> 1,
                'spbId'		=> 5,
                'noId'		=> 99
            ],
            'order'	=> 'name'
        ]);

        $meals = Tourvisor\Meals::find([
            'order' => 'id DESC'
        ]);

		$title = 'Путевки и туры в отель ' . $hotel->name . ' из ' . $this->city->nameRod . ' по самым низким ценам на ';

		$this->params->search->where->hotels = $hotel->db->id;

		$this->view->setVars([
			'departures' => $departures,
			'params' => $this->params,
			'hotel' => $hotel,
			'title' => $title,
			'meals' => $meals,
			'page' => 'hotel',
			'operator' => $operator
		]);
	}

}
