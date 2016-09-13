<?php

use Phalcon\Http\Response			as Response,
	Models\Tourvisor				as Tourvisor,
	Models\Cities					as Cities,
	Frontend\Models\SearchQueries	as SearchQueries;

class HotelController extends ControllerFrontend
{
	public function indexAction($name, $id)
	{
		$result = Utils\Tourvisor::getMethod('hotel', array(
			'hotelcode'		=> $id,
			'imgwidth'		=> 400,
			'imgheight'		=> 260
		));
		
		$hotel = $result->data->hotel;

		$dbHotel = Tourvisor\Hotels::findFirst($id);
		
		$dbTypes = new stdClass();
		
		$dbTypes->active = $dbHotel->active;
		$dbTypes->relax = $dbHotel->relax;
		$dbTypes->family = $dbHotel->family;
		$dbTypes->health = $dbHotel->health;
		$dbTypes->city = $dbHotel->city;
		$dbTypes->beach = $dbHotel->beach;
		$dbTypes->deluxe = $dbHotel->deluxe;
		
		$types = [];
		
		foreach($dbTypes as $key=>$value)
		{
			if($value == 1)
			{
				$types[$key] = Utils\Text::humanize('types',$key);
			}
			
		}

		$operator = $this->request->get('operator');
		
		$hotel->types = $types;

		$hotel->db = $dbHotel;
		
		$hotel->humanizeRating = Utils\Text::humanize('rating',$hotel->rating);
		
		$hotel->coord1 = str_replace(',','.',$hotel->coord1);
		$hotel->coord2 = str_replace(',','.',$hotel->coord2);

		/*$hotel->description = isset($hotel->description) ? $hotel->description : '';
		$hotel->build = isset($hotel->build) ? $hotel->build : '';
		$hotel->repair = isset($hotel->repair) ? $hotel->repair : '';
		$hotel->phone = isset($hotel->phone) ? $hotel->phone : '';
		$hotel->site = isset($hotel->site) ? $hotel->site : '';*/

		//print_r($hotel);
		//die();

		$title = 'Туры в ' . $hotel->name . ' из ' . $this->currentCity->name_rod . ' на ';

		$this->view->setVars([
			'departures'	=> Tourvisor\Departures::find(),
			'params'		=> $this->params,
			'hotel'			=> $hotel,
			'title'			=> $title,
			'page'			=> 'hotel',
			'operator'		=> $operator
		]);
	}
	
}
