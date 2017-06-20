<?php

namespace Frontend\Controllers;

use Phalcon\Db;
use Phalcon\Http\Response;
use Models\Tourvisor;
use Backend\Models\Requests;
use Backend\Models\Payments;
use Backend\Models\Tourists;
use Backend\Models\RequestTourists;
use Frontend\Models\SearchQueries;
use Backend\Controllers\EmailController;

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

		$fullPrice = (int) $form->price;
		$tour = $form->tour;

		$request = new Requests();

		$request->origin = Requests::ORIGIN_WEB;

		//Клиент
		$request->subjectSurname		= $form->person->surname;
		$request->subjectName			= $form->person->name;
		$request->subjectPatronymic		= $form->person->patronymic;
		$request->subjectPhone			= $form->person->phone;
		$request->subjectEmail			= $form->person->email;
		$request->comment				= $form->comments;

		//Данные тура
		$request->hotelName			= $tour->hotelname;
		$request->hotelCountry		= $tour->countryname;
		$request->hotelRegion		= $tour->hotelregionname;
		$request->hotelDate			= $tour->flydate;
		$request->hotelNights		= $tour->nights;
		$request->hotelPlacement	= $tour->placement;
		$request->hotelMeal			= $tour->meal;
		$request->hotelRoom			= $tour->room;

		$flight = $form->flight;

		if($flight)
		{
			$request->flightToNumber			= $flight->forward[0]->number;
			$request->flightToDepartureDate		= $flight->dateforward;
			$request->flightToDepartureTime		= $flight->forward[0]->departure->time;
			$request->flightToDepartureTerminal	= $flight->forward[0]->departure->port->id;
			$request->flightToArrivalDate		= $flight->dateforward;
			$request->flightToArrivalTime		= $flight->forward[0]->arrival->time;
			$request->flightToArrivalTerminal	= $flight->forward[0]->arrival->port->id;
			$request->flightToCarrier			= $flight->forward[0]->company->name;
			$request->flightToPlane				= $flight->forward[0]->plane;
			$request->flightToClass				= '';

			$request->flightFromNumber				= $flight->backward[0]->number;
			$request->flightFromDepartureDate		= $flight->datebackward;
			$request->flightFromDepartureTime		= $flight->backward[0]->departure->time;
			$request->flightFromDepartureTerminal	= $flight->backward[0]->departure->port->id;
			$request->flightFromArrivalDate			= $flight->datebackward;
			$request->flightFromArrivalTime			= $flight->backward[0]->arrival->time;
			$request->flightFromArrivalTerminal		= $flight->backward[0]->arrival->port->id;
			$request->flightFromCarrier				= $flight->backward[0]->company->name;
			$request->flightFromPlane				= $flight->backward[0]->plane;
			$request->flightFromClass				= '';
		}

		$request->tourOperatorId	= $tour->operatorcode;
		$request->tourOperatorLink	= $tour->operatorlink;
		$request->departureId		= $tour->departurecode;

		$tourists = [];

		foreach($form->tourists as $i => $formTourist)
		{
			$tourist = new \stdClass();

			if(isset($formTourist->visa))
			{
				$fullPrice += $tour->visa;
			}

			$tourist->passport_name		= $formTourist->firstname;
			$tourist->passport_surname	= $formTourist->lastname;
			$tourist->passport_number	= $formTourist->passport;
			$tourist->passport_endDate	= $formTourist->end_date;
			$tourist->passport_issued	= $formTourist->issue;
			$tourist->birthDate			= $formTourist->birth;
			$tourist->gender			= ($formTourist->gender === 'man') ? 'm' : 'f';
			$tourist->visa				= ($formTourist->visa === 'on') ? 1 : 0;
			$tourist->nationality		= $formTourist->nationality;

			$touristModel = Tourists::addOrUpdate($tourist);

			$tourists[] = $touristModel;
		}

		$request->price = $fullPrice;

		if($type === 'office')
		{
			$request->branch_id = (int) $form->branch;
		}

		if($request->save())
		{


			foreach($tourists as $tourist)
			{
				$requestTourist = new RequestTourists();
				$requestTourist->requestId = $request->id;
				$requestTourist->touristId = $tourist->id;

				$requestTourist->save();
			}

			if($type === 'online')
			{
				$payment = Payments::findFirst('requestId = ' . $request->id);

				$response->setJsonContent(['res' => '/pay/' . $payment->id ]);
			}
			else if($type === 'office')
			{
				$mailController = new EmailController();
				$mailController->sendBranchNotification($request);
				$response->setJsonContent(['res' => '/' ]);
			}
			else
			{
				$response->setJsonContent(['res' => '/' ]);
			}

			//Отправляем email
			$emailController = new EmailController();
			$emailController->sendRequest($type, $request);
			$emailController->sendAdminNotification($request);
		}
		else
		{
			$response->setJsonContent(['error' => 'save error' ]);
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
		$response->setJsonContent(['status' => 'ok' ]);

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

		$dbCountries = Tourvisor\Countries::find('active = 1');

		$countries = [];
		foreach($dbCountries as $country)
		{
			$countries[] = $country;
		}

		$dbRegions = $this->db->fetchAll('
			SELECT r.name, r.id, r.countryId, c.name AS country_name FROM tourvisor_regions AS r
			INNER JOIN tourvisor_countries AS c ON c.active = 1 AND c.id = r.countryId;
		', Db::FETCH_OBJ);

		$regions = [];
		foreach($dbRegions as $item)
		{
			$region = new \stdClass();
			$region->country = $item->countryId;
			$region->country_name = $item->country_name;
			$regions[] = $region;
		}

		$response->setJsonContent([
			'countries'	=> $countries,
			'regions'	=> $regions
		]);

		$response->setHeader('Content-Type', 'application/json; charset=UTF-8');

		return $response;
	}

	public function departuresAction()
	{
		$response = new Response();

		$dbDepartures = Tourvisor\Departures::find();

		$departures = [];
		foreach($dbDepartures as $departure)
		{
			$departures[] = $departure;
		}
		$response->setJsonContent([
			'departures'	=> $departures
		]);

		$response->setHeader('Content-Type', 'application/json; charset=UTF-8');

		return $response;
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


	public function resultsAction($tourvisorId)
    {
	    $response = new Response();
	    
		$params = [
			'requestid'		=> $tourvisorId,
			'type'			=> 'result'
		];
		
		if (isset($_GET['page'])) {
			$params['page'] = $_GET['page'];
		}
		if (isset($_GET['limit'])) {
			$params['onpage'] = $_GET['limit'];
		}
		
		$result = \Utils\Tourvisor::getMethod('result', $params);
		
		$res = new \stdClass();
		
		$hotels = [];
		
		$res->status = $result->data->status;
		
		if (!empty($result->data->result->hotel))
		{
			foreach($result->data->result->hotel as $hotel)
			{
				$resultHotel = new \stdClass();
				$resultHotel->id = $hotel->hotelcode;
				$resultHotel->name = $hotel->hotelname;
				$resultHotel->stars = $hotel->hotelstars;
				$resultHotel->rating = $hotel->hotelrating;
				if($hotel->isdescription) {
					$resultHotel->description = $hotel->hoteldescription;
				}
				if($hotel->isphoto) {
					$resultHotel->image = $hotel->picturelink;
				}
				$resultHotel->price = $hotel->price;
				
				$resultHotel->country = new \stdClass();
				$resultHotel->country->id = $hotel->countrycode;
				$resultHotel->country->name = $hotel->countryname;
				
				$resultHotel->region = new \stdClass();
				$resultHotel->region->id = $hotel->regioncode;
				$resultHotel->region->name = $hotel->regionname;
				$resultHotel->tours = $hotel->tours->tour;

				$urlName = str_ireplace([' ','&'], ['_','And'], ucwords(strtolower($resultHotel->name)));
				$resultHotel->hotelLink = '/hotel/' . $urlName . '-' . $resultHotel->id;
				
				$hotels[$hotel->hotelcode] = $resultHotel;	
			}
			
			$hotelIds = array_keys($hotels);
			
			if(!empty($hotelIds))
			{			
				$dbHotels = $this->modelsManager->createBuilder()
					->from(Tourvisor\Hotels::name())
					->inWhere('id', $hotelIds)
					->getQuery()
					->execute();
					
				foreach($dbHotels as $dbHotel)
				{
					$types = new \stdClass();
					$types->active = $dbHotel->active;
					$types->relax = $dbHotel->relax;
					$types->family = $dbHotel->family;
					$types->health = $dbHotel->health;
					$types->city = $dbHotel->city;
					$types->beach = $dbHotel->beach;
					$types->deluxe = $dbHotel->deluxe;	
					$hotels[$dbHotel->id]->types = $types;
				}
				
			}
		}
		
		$res->hotels = array_values($hotels);
		
	    $response->setJsonContent($res);

	    $response->setHeader('Content-Type', 'application/json; charset=UTF-8');
	    
	    return $response;
    }
    
    public function statusAction($tourvisorId)
    {
	    $response = new Response();
	    
		$params = array(
			'requestid'		=> $tourvisorId,
			'type'			=> 'status'
		);
		$result = \Utils\Tourvisor::getMethod('result', $params);
		
		$res = new \stdClass();
		$res->status = $result->data->status;
			    
	    $response->setJsonContent($res);

	    $response->setHeader('Content-Type', 'application/json; charset=UTF-8');
	    
	    return $response;
    }

    public function searchAction()
    {
	    $response = new Response();
	    
	    $params = (object) $this->request->get('params');
	    
		$searchQuery = new SearchQueries();
		$searchQuery->fillFromParams($params);

	    $path = '/search/';

	    if($searchQuery->isHotelQuery())
	    {
		    $path = '/search/hotel/';
	    }

	    $response->setJsonContent(['url' =>  $path . $searchQuery->buildQueryString() ]);

	    $response->setHeader('Content-Type', 'application/json; charset=UTF-8');
	    
	    return $response;
    }

	public function searchHotelAction()
	{
		$response = new Response();

		$params = (object) $this->request->get('params');

		$searchQuery = new SearchQueries();
		$searchQuery->fillFromParams($params);
		$searchQuery->run();

		$response->setJsonContent(['tourvisorId' => $searchQuery->tourvisorId, 'query'=>$searchQuery->toArray() ]);

		$response->setHeader('Content-Type', 'application/json; charset=UTF-8');

		return $response;
	}

    public function tourAction($tourvisorId)
    {
	    $response = new Response();
	    
		$params = array(
			'tourid'		=> $tourvisorId,
			'flights'		=> 1
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
			'hotelcode'		=> $tourvisorId,
			'imgwidth'		=> 400,
			'imgheight'		=> 260
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
}