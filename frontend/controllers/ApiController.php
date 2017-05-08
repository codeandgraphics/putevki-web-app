<?php

use Phalcon\Http\Response;
use Phalcon\Cache\Backend\File as Cache;
use Phalcon\Cache\Frontend\Data as CacheData;

use Models\Api\Error;
use Models\Api\JSONResponse;
use Models\Api\SearchQuery;
use Models\Api\Entities;

class ApiController extends ControllerFrontend
{
	protected $_cache;

	public function initialize()
	{
		parent::initialize();

		$cacheData = new CacheData(
			array(
				'lifetime' => 172800
			)
		);

		$this->_cache = new Cache(
			$cacheData,
			array(
				'cacheDir' => '../app/cache/'
			)
		);

		$this->view->disable();
	}

	public function indexAction()
	{
		$response = new Response();

		$data = [];

		$response->setJsonContent($data);

		return $response;
	}

	public function requestAction(){
		if($this->request->isPost()) {

			$data = $this->request->getJsonRawBody();

			$request = new \Backend\Models\Requests();

			$order = $data->order;

			//Клиент
			$request->subjectName			= $order->subject->name;
			$request->subjectPhone			= $order->subject->phone;
			$request->subjectEmail			= $order->subject->email;

			//Данные тура
			$request->hotelName			= $order->hotel->name;
			$request->hotelCountry		= $order->hotel->country;
			$request->hotelRegion		= $order->hotel->region;

			$request->hotelDate			= $order->hotel->date;
			$request->hotelNights		= $order->hotel->nights;
			$request->hotelPlacement	= $order->hotel->placement;
			$request->hotelMeal			= $order->hotel->meal;
			$request->hotelRoom			= $order->hotel->room;

			if($order->flight)
			{
				$request->flightToNumber			= $order->flight->to->number;
				$request->flightToDepartureDate		= $order->flight->to->departure->date;
				$request->flightToDepartureTime		= $order->flight->to->departure->time;
				$request->flightToDepartureTerminal	= $order->flight->to->departure->port;
				$request->flightToArrivalDate		= $order->flight->to->arrival->date;
				$request->flightToArrivalTime		= $order->flight->to->arrival->time;
				$request->flightToArrivalTerminal	= $order->flight->to->arrival->port;
				$request->flightToCarrier			= $order->flight->to->carrier;
				$request->flightToPlane				= $order->flight->to->plane;

				$request->flightFromNumber				= $order->flight->from->number;
				$request->flightFromDepartureDate		= $order->flight->from->departure->date;
				$request->flightFromDepartureTime		= $order->flight->from->departure->time;
				$request->flightFromDepartureTerminal	= $order->flight->from->departure->port;
				$request->flightFromArrivalDate			= $order->flight->from->arrival->date;
				$request->flightFromArrivalTime			= $order->flight->from->arrival->time;
				$request->flightFromArrivalTerminal		= $order->flight->from->arrival->port;
				$request->flightFromCarrier				= $order->flight->from->carrier;
				$request->flightFromPlane				= $order->flight->from->plane;
				$request->flightFromClass				= '';
			}

			$request->tourOperatorId = $order->tour->operator;
			$request->price = $order->tour->price;
			$request->departureId		= $order->tour->from;

			if($request->save()) {
				return new JSONResponse(Error::NO_ERROR, ['success' => true]);
			} else {
				return new JSONResponse(Error::API_ERROR);
			}
		}
	}


	public function dictionariesAction() {
		$response = array(
			'cities' => [],
			'departures' => [],
			'destinations' => [],
			'stars' => [],
			'meal' => [],
			'rating' => [
				new Entities\Rating(2, '3.0 и выше'),
				new Entities\Rating(3, '3.5 и выше'),
				new Entities\Rating(4, '4.0 и выше'),
				new Entities\Rating(5, '4.5 и выше')
			]
		);

		/* Offices */
		$citiesBuilder = $this->modelsManager->createBuilder()
			->columns([
				'branch.*',
				'city.*'
			])
			->addFrom(\Models\Cities::name(), 'city')
			->join(
				\Models\Branches::name(),
				'branch.cityId = city.id',
				'branch'
			)
			->where('city.active = 1 AND branch.active = 1');

		$cityItems = $citiesBuilder->getQuery()->execute();

		$cities = [];
		foreach ($cityItems as $item) {
			if(!array_key_exists($item->city->id, $cities)) {
				$cities[$item->city->id] = new Entities\City($item->city);
			}

			$cities[$item->city->id]->offices[] = new Entities\Office($item->branch);
		}

		$response['cities'] = array_values($cities);

		/* Departures */
		$departures = \Models\Tourvisor\Departures::find();

		foreach ($departures as $departure) {
			$response['departures'][] = new Entities\Departure($departure);
		}

		/* Destinations */
		$builder = $this->modelsManager->createBuilder()
			->columns([
				'region.*',
				'country.*'
			])
			->addFrom(\Models\Tourvisor\Regions::name(), 'region')
			->join(
				\Models\Tourvisor\Countries::name(),
				'region.countryId = country.id',
				'country'
			)
			->where('country.active = 1')
			->orderBy('country.popular DESC, country.name, region.popular DESC, region.name');

		$items = $builder->getQuery()->execute();

		foreach($items as $item) {
			$country = $item->country;
			$region = $item->region;
			$countryId = (int) $region->countryId;

			if(!array_key_exists($countryId, $response['destinations'])) {
				$response['destinations'][$countryId] = new Entities\Country($country);
			}

			$response['destinations'][$region->countryId]->regions[] = new Entities\Region($region);
		}

		$response['destinations'] = array_values($response['destinations']);

		$meals = \Models\Tourvisor\Meals::find();
		foreach($meals as $meal) {
			$response['meal'][] = new Entities\Meal($meal);
		}

		$stars = \Models\Tourvisor\Stars::find();
		foreach($stars as $star) {
			$response['stars'][] = new Entities\Star($star);
		}

		return new JSONResponse(Error::NO_ERROR, $response);
	}

	public function initSearchAction()
	{
		$body = $this->request->getJsonRawBody();

		$query = new SearchQuery($body->params);

		$searchId = $query->run();

		if($searchId) {
			return new JSONResponse(Error::NO_ERROR, ['searchId' => $searchId ]);
		} else {
			return new JSONResponse(Error::API_ERROR);
		}

	}

	public function searchStatusAction() {
		$searchId = $this->request->get('searchId');

		$params = array(
			'requestid'		=> $searchId,
			'type'			=> 'status'
		);

		$result = Utils\Tourvisor::getMethod('result', $params);

		if(property_exists($result, 'data') && property_exists($result->data, 'status')) {
			return new JSONResponse(Error::NO_ERROR, ['status' => new Entities\Status($result->data->status)]);
		} else {
			return new JSONResponse(Error::API_ERROR);
		}
	}

	public function searchResultAction() {

		$searchId = $this->request->get('searchId');

		$params = array(
			'requestid'		=> $searchId,
			'type'			=> 'result'
		);

		$result = Utils\Tourvisor::getMethod('result', $params);

		if(
			property_exists($result, 'data') &&
			property_exists($result->data, 'status')
		) {

			$status = new Entities\Status($result->data->status);
			$hotels = [];

			if(property_exists($result->data, 'result')) {
				foreach($result->data->result->hotel as $hotel) {
					$hotels[] = new Entities\Hotel($hotel);
				}
			}

			return new JSONResponse(Error::NO_ERROR, ['status' => $status, 'hotels' => $hotels]);
		} else {
			return new JSONResponse(Error::API_ERROR);
		}
	}

	public function fullSearchResultAction() {

		$searchId = $this->request->get('searchId');

		$params = array(
			'requestid'		=> $searchId,
			'type'			=> 'result',
			'onpage'        => 999
		);

		$result = Utils\Tourvisor::getMethod('result', $params);

		if(
			property_exists($result, 'data') &&
			property_exists($result->data, 'status')
		) {

			$status = new Entities\Status($result->data->status);
			$hotels = [];

			if(property_exists($result->data, 'result')) {
				foreach($result->data->result->hotel as $hotel) {
					$hotels[] = new Entities\Hotel($hotel);
				}
			}

			return new JSONResponse(Error::NO_ERROR, ['status' => $status, 'hotels' => $hotels]);
		} else {
			return new JSONResponse(Error::API_ERROR);
		}
	}

	public function actualizeTourAction() {
		$tourId = $this->request->get('tourId');

		$params = array(
			'tourid'		=> $tourId
		);

		$result = Utils\Tourvisor::getMethod('actdetail', $params);

		//return new JSONResponse(Error::NO_ERROR, ['result' => $result]);

		return new JSONResponse(Error::NO_ERROR, ['details' => new Entities\TourDetails($result)]);
	}

	public function hotelAction() {

		$hotelId = $this->request->get('hotelId');

		$params = array(
			'hotelcode'		=> $hotelId,
			'removetags'    => 1
		);

		$result = Utils\Tourvisor::getMethod('hotel', $params);

		if(
			property_exists($result, 'data') &&
			property_exists($result->data, 'hotel')
		) {
			$hotel = new Entities\HotelFull($result->data->hotel);
			$hotel->id = (int) $hotelId;
			return new JSONResponse(Error::NO_ERROR, ['hotel' => $hotel]);
		} else {
			return new JSONResponse(Error::API_ERROR);
		}
	}

	public function hotelsAction()
	{
		$response = new Response();

		$query = mb_strtoupper($this->request->get('query'), 'UTF-8');

		$hotels = [];

		if(mb_strlen($query,'UTF-8') >= 2)
		{
			$builder = $this->modelsManager->createBuilder()
				->columns([
					'hotel.id',
					'hotel.name',
					'country.id AS country',
					'region.id AS region',
					'country.name AS countryName',
					'region.name AS regionName'
				])
				->addFrom(\Models\Tourvisor\Hotels::name(), 'hotel')
				->join(
					\Models\Tourvisor\Countries::name(),
					'country.id = hotel.countryId',
					'country'
				)
				->join(
					\Models\Tourvisor\Regions::name(),
					'region.id = hotel.regionId',
					'region'
				)
				->where('country.active = 1')
				->andWhere('hotel.name LIKE :query:')
				->limit(20);

			$dbHotels = $builder->getQuery()->execute([ 'query' => '%' . $query . '%' ]);

			foreach($dbHotels as $hotel)
			{
				$hotels[] = $hotel;
			}
		}

		$response->setJsonContent($hotels);

		$response->setHeader('Content-Type', 'application/json; charset=UTF-8');

		return $response;
	}

	public function yandex_dictionariesAction($method)
	{
		$response = new Response();

		$className = '\Models\References\\' . ucfirst($method);

		if(class_exists($className))
		{
			$cacheKey = 'yandex_api.method.' . $method;

			$content = $this->_cache->get($cacheKey);

			if($content === null)
			{
				$content = $className::getReferenced();
				$this->_cache->save($content);
			}
		}
		else
		{
			$content = [
				'error'	=> "Method not exists"
			];
		}

		$response->setJsonContent($content);

		return $response;
	}
}