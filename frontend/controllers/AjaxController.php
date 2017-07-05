<?php

namespace Frontend\Controllers;

use Frontend\Models\Params;
use Models\Origin;
use Models\SearchQuery;
use Models\Entities;
use Phalcon\Db;
use Phalcon\Http\Response;
use Models\Tourvisor;
use Backend\Models\Requests;
use Backend\Models\Payments;
use Backend\Models\Tourists;
use Backend\Models\RequestTourists;
use Backend\Controllers\EmailController;
use Utils\Tourvisor as TourvisorUtils;

class AjaxController extends BaseController
{

	public function initialize()
	{
		$this->view->disable();
	}

	public function formOnlineAction()
	{
		$response = new Response();

		$form = json_decode($_POST['data']);
		$type = $_POST['type'];

		$fullPrice = (int)$form->price;
		$tour = $form->tour;

		$request = new Requests();

		$request->origin = Requests::ORIGIN_WEB;

		//Клиент
		$request->subjectSurname = $form->person->surname;
		$request->subjectName = $form->person->name;
		$request->subjectPatronymic = $form->person->patronymic;
		$request->subjectPhone = $form->person->phone;
		$request->subjectEmail = $form->person->email;
		$request->comment = $form->comments;

		//Данные тура
		$request->hotelName = $tour->hotelname;
		$request->hotelCountry = $tour->countryname;
		$request->hotelRegion = $tour->hotelregionname;
		$request->hotelDate = $tour->flydate;
		$request->hotelNights = $tour->nights;
		$request->hotelPlacement = $tour->placement;
		$request->hotelMeal = $tour->meal;
		$request->hotelRoom = $tour->room;

		$flight = $form->flight;

		if ($flight) {
			$request->flightToNumber = $flight->forward[0]->number;
			$request->flightToDepartureDate = $flight->dateforward;
			$request->flightToDepartureTime = $flight->forward[0]->departure->time;
			$request->flightToDepartureTerminal = $flight->forward[0]->departure->port->id;
			$request->flightToArrivalDate = $flight->dateforward;
			$request->flightToArrivalTime = $flight->forward[0]->arrival->time;
			$request->flightToArrivalTerminal = $flight->forward[0]->arrival->port->id;
			$request->flightToCarrier = $flight->forward[0]->company->name;
			$request->flightToPlane = $flight->forward[0]->plane;
			$request->flightToClass = '';

			$request->flightFromNumber = $flight->backward[0]->number;
			$request->flightFromDepartureDate = $flight->datebackward;
			$request->flightFromDepartureTime = $flight->backward[0]->departure->time;
			$request->flightFromDepartureTerminal = $flight->backward[0]->departure->port->id;
			$request->flightFromArrivalDate = $flight->datebackward;
			$request->flightFromArrivalTime = $flight->backward[0]->arrival->time;
			$request->flightFromArrivalTerminal = $flight->backward[0]->arrival->port->id;
			$request->flightFromCarrier = $flight->backward[0]->company->name;
			$request->flightFromPlane = $flight->backward[0]->plane;
			$request->flightFromClass = '';
		}

		$request->tourOperatorId = $tour->operatorcode;
		$request->tourOperatorLink = $tour->operatorlink;
		$request->departureId = $tour->departurecode;

		$tourists = [];

		foreach ($form->tourists as $i => $formTourist) {
			$tourist = new \stdClass();

			if (property_exists($formTourist, 'visa')) {
				$fullPrice += $tour->visa;
			}

			$tourist->passport_name = $formTourist->firstname;
			$tourist->passport_surname = $formTourist->lastname;
			$tourist->passport_number = $formTourist->passport;
			$tourist->passport_endDate = $formTourist->end_date;
			$tourist->passport_issued = $formTourist->issue;
			$tourist->birthDate = $formTourist->birth;
			$tourist->gender = ($formTourist->gender === 'man') ? 'm' : 'f';
			$tourist->visa = (property_exists($formTourist, 'visa') && $formTourist->visa === 'on') ? 1 : 0;
			$tourist->nationality = $formTourist->nationality;

			$touristModel = Tourists::addOrUpdate($tourist);

			$tourists[] = $touristModel;
		}

		$request->price = $fullPrice;

		if ($type === 'office') {
			$request->branch_id = (int)$form->branch;
		}

		if ($request->save()) {

			foreach ($tourists as $tourist) {
				$requestTourist = new RequestTourists();
				$requestTourist->requestId = $request->id;
				$requestTourist->touristId = $tourist->id;

				$requestTourist->save();
			}

			if ($type === 'online') {
				$payment = Payments::findFirst('requestId = ' . $request->id);

				$response->setJsonContent(['res' => '/pay/' . $payment->id]);
			} else if ($type === 'office') {
				$mailController = new EmailController();
				$mailController->sendBranchNotification($request);
				$response->setJsonContent(['res' => '/']);
			} else {
				$response->setJsonContent(['res' => '/']);
			}

			//Отправляем email
			$emailController = new EmailController();
			$emailController->sendRequest($type, $request);
			$emailController->sendAdminNotification($request);
		} else {
			$response->setJsonContent(['error' => 'save error']);
		}

		$response->setHeader('Content-Type', 'application/json; charset=UTF-8');

		return $response;
	}

	public function findTourAction()
	{
		$response = new Response();
		$data = json_decode($_POST['data']);

		$emailController = new EmailController();
		$emailController->sendFindTour($data);
		$response->setJsonContent(['status' => 'ok']);

		$response->setHeader('Content-Type', 'application/json; charset=UTF-8');

		return $response;
	}

	public function tourHelpAction()
	{
		$response = new Response();
		$phone = $_POST['data']['findPhone'];
		$queries = $_POST['data']['query'];

		$emailController = new EmailController();
		$emailController->sendTourHelp($phone, $queries);
		$response->setJsonContent(['status' => 'ok']);

		$response->setHeader('Content-Type', 'application/json; charset=UTF-8');

		return $response;
	}

	public function destinationsAction()
	{
		$response = new Response();

		$items = Tourvisor\Countries::find('active = 1');

		$countries = [];
		foreach ($items as $item) {
		    $country = new Entities\Country($item);
		    unset($country->regions);
			$countries[] = $country;
		}

		$dbRegions = $this->db->fetchAll('
			SELECT r.name, r.id, r.countryId, c.name AS country_name FROM tourvisor_regions AS r
			INNER JOIN tourvisor_countries AS c ON c.active = 1 AND c.id = r.countryId;
		', Db::FETCH_OBJ);

		$regions = [];
		foreach ($dbRegions as $item) {
			$region = new \stdClass();
			$region->id = (int) $item->id;
			$region->name = $item->name;
			$region->country = (int) $item->countryId;
			$region->countryName = $item->country_name;
			$regions[] = $region;
		}

		$response->setJsonContent([
			'countries' => $countries,
			'regions' => $regions
		]);

		$response->setHeader('Content-Type', 'application/json; charset=UTF-8');

		return $response;
	}

	public function departuresAction()
	{
		$response = new Response();

		$items = Tourvisor\Departures::find();

		$departures = [];
		foreach ($items as $item) {
			$departures[] = $item->format();
		}
		$response->setJsonContent([
			'departures' => $departures
		]);

		$response->setHeader('Content-Type', 'application/json; charset=UTF-8');

		return $response;
	}

	public function hotelsAction()
	{
		$response = new Response();

		$query = mb_strtoupper($this->request->get('query'), 'UTF-8');

		$hotels = [];

		if (mb_strlen($query, 'UTF-8') >= 2) {
			$builder = $this->modelsManager->createBuilder()
				->columns([
					'hotel.id',
					'hotel.name',
					'country.id AS country',
					'region.id AS region',
					'country.name AS countryName',
					'region.name AS regionName'
				])
				->addFrom(Tourvisor\Hotels::name(), 'hotel')
				->join(
					Tourvisor\Countries::name(),
					'country.id = hotel.countryId',
					'country'
				)
				->join(
					Tourvisor\Regions::name(),
					'region.id = hotel.regionId',
					'region'
				)
				->where('country.active = 1')
				->andWhere('hotel.name LIKE :query:')
				->limit(10);

			$dbHotels = $builder->getQuery()->execute(['query' => '%' . $query . '%']);

			foreach ($dbHotels as $hotel) {
				$hotels[] = $hotel;
			}
		}

		$response->setJsonContent($hotels);

		$response->setHeader('Content-Type', 'application/json; charset=UTF-8');

		return $response;
	}

	public function searchAction()
	{
		$response = new Response();

		$formParams = (object)$this->request->get('params');

		$params = Params::getInstance();
		$params->search->fromSearchForm($formParams);

		$path = '/search/';

		if ($params->search->isHotelQuery()) {
			$path = '/search/hotel/';
		}

		$response->setJsonContent(['url' => $path . $params->search->buildQueryString()]);

		$response->setHeader('Content-Type', 'application/json; charset=UTF-8');

		return $response;
	}

	public function searchHotelAction()
	{
		$response = new Response();

        $formParams = (object)$this->request->get('params');

        $params = Params::getInstance();
        $params->search->fromSearchForm($formParams);

        $searchQuery = new SearchQuery();
        $searchQuery->fromParams($params->search);
        $searchId = $searchQuery->run(Origin::WEB);

		$response->setJsonContent(['searchId' => $searchId]);

		$response->setHeader('Content-Type', 'application/json; charset=UTF-8');

		return $response;
	}

	public function tourAction($tourvisorId)
	{
		$response = new Response();

		$params = array(
			'tourid' => $tourvisorId,
			'flights' => 1
		);
		$result = \Utils\Tourvisor::getMethod('actualize', $params);

		$response->setJsonContent($result);

		$response->setHeader('Content-Type', 'application/json; charset=UTF-8');

		return $response;
	}

	public function tourDetailAction($tourvisorId)
	{
		$response = new Response();

		$detailData = \Utils\Tourvisor::getMethod('actdetail', array(
			'tourid' => $tourvisorId
		));

		$response->setJsonContent($detailData);

		$response->setHeader('Content-Type', 'application/json; charset=UTF-8');

		return $response;
	}

	public function hotelAction($tourvisorId)
	{
		$response = new Response();

		$params = array(
			'hotelcode' => $tourvisorId,
			'imgwidth' => 400,
			'imgheight' => 260
		);
		$result = \Utils\Tourvisor::getMethod('hotel', $params);

		$response->setJsonContent($result);

		$response->setHeader('Content-Type', 'application/json; charset=UTF-8');

		return $response;
	}

	public function regionsAction($countryId)
	{
		$response = new Response();

		$result = Tourvisor\Regions::find("countryId = $countryId");

		$response->setJsonContent($result->toArray());

		$response->setHeader('Content-Type', 'application/json; charset=UTF-8');

		return $response;
	}

	public function hotToursAction()
	{
		$response = new Response();

		$country = $this->request->get('country', 'int');
		$region = $this->request->get('region', 'int');
		$items = $this->request->get('items', 'int');

		$params = [
			'city' => $this->request->get('departure', 'int'),
			'items' => $items ? : 8,
		];

		if($country) {
			$params['countries'] = $country;
		}
		if($region) {
			$params['regions'] = $region;
		}

		$tours = TourvisorUtils::getMethod('hottours', $params);

		$response->setJsonContent($tours->hottours->tour);

		$response->setHeader('Content-Type', 'application/json; charset=UTF-8');

		return $response;
	}
}