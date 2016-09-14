<?php

use Phalcon\Http\Response				as Response,
	Models\Tourvisor					as Tourvisor,
	Backend\Models\Requests				as Requests,
	Backend\Models\Payments				as Payments,
	Backend\Models\Tourists				as Tourists,
	Backend\Models\RequestTourists		as RequestTourists,
	Frontend\Models\SearchQueries		as SearchQueries,
	Backend\Controllers\EmailController	as EmailController;

class AjaxController extends ControllerFrontend
{
	
    public function indexAction()
    {
	   /* $response = new Response();
	    
	    $str2 = '{"ts_client_surname":"\u0412\u044f\u0437\u0435\u043c\u0441\u043a\u0438\u0439","ts_client_name":"\u0413\u0435\u043e\u0440\u0433\u0438\u0439","ts_client_otch":"\u0418\u0433\u043e\u0440\u0435\u0432\u0438\u0447","ts_client_adr":"\u0414\u0443\u0431\u043d\u0430, \u0443\u043b. \u0421\u0430\u0445\u0430\u0440\u043e\u0432\u0430 11, 21","ts_client_phone":"89263488996","ts_client_email":"msnake@avantico.ru","ts_operator_code":"23","ts_country_code":"47","ts_country_name":"\u0420\u043e\u0441\u0441\u0438\u044f","ts_departure_code":"1","ts_hotel_code":"30297","ts_hotel_name":"\u041a\u0420\u042b\u041c\u0421\u041a\u0418\u0415 \u0417\u041e\u0420\u0418","ts_flydate":"24.07.2015","ts_nights":"7","ts_tour_text":"MSK, \u041a\u0440\u044b\u043c, 05.06-22.09.15,, \u0430\/\u043a \"\u0422\u0440\u0430\u043d\u0441\u0430\u044d\u0440\u043e\"","ts_meal":"BB","ts_room":"1-\u043c\u0435\u0441\u0442\u043d\u044b\u0439 \u0432 \u0431\u043b\u043e\u043a\u0435","ts_placement":"1 \u0432\u0437\u0440\u043e\u0441\u043b\u044b\u0439","ts_adults":"1","ts_child":"0","ts_price_usd":0,"ts_tour_id":"234029608242","ts_visa_rub":0,"ts_comments":"\u041f\u0440\u0438\u0432\u0435\u0442!","ts_flight_id":0,"ts_people_count":1,"tourist_surname":["\u0412\u044f\u0437\u0435\u043c\u0441\u043a\u0438\u0439"],"tourist_name":["\u0413\u0435\u043e\u0440\u0433\u0438\u0439"],"tourist_sex":["m"],"tourist_passport_series":[""],"tourist_passport_number":["12\u21161312331"],"tourist_passport_issue_date":[0],"tourist_passport_end_date":["11.08.2010"],"tourist_birth_date":["11.08.1989"],"tourist_passport_issue_by":["\u041e\u0423\u0424\u041c\u0421 \u0414\u0443\u0431\u043d\u044b"],"tourist_birth_country":[""],"tourist_citizen":["\u0420\u043e\u0441\u0441\u0438\u044f"],"tourist_visa":[0],"ts_price_rub":23313}';
	    
	    $tour = json_decode($str2);
	    $date = new \DateTime();
	    
		$order = new \Models\Orders();
		$order->date = $date->format('Y-m-d H:i:s');
		$order->tour = $tour;
		$order->status = 1;
		$order->save();
		
	    echo 'asd';
		//$response->setJsonContent();
	    
	    return $response;*/
	    
	    echo 'asd';
    }
    
    public function formOnlineAction()
    {
		$response = new Response();
		
		$form = json_decode($_POST['data']);
		$type = $_POST['type'];

		$fullPrice = (int) $form->price;
		$tour = $form->tour;

		$request = new Requests();

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

		$flightId = $form->flight;
		$flight = $tour->flights[$flightId];

		if($flight)
		{
			$request->flightToNumber			= $flight->forward->flightnum;
			$request->flightToDepartureDate		= $flight->forward->flydate;
			$request->flightToDepartureTime		= $flight->forward->timefrom;
			$request->flightToDepartureTerminal	= $flight->forward->terminalfrom;
			$request->flightToArrivalDate		= $flight->forward->flydate;
			$request->flightToArrivalTime		= $flight->forward->timeto;
			$request->flightToArrivalTerminal	= $flight->forward->terminalto;
			$request->flightToCarrier			= $flight->forward->aircompanyrus;
			$request->flightToPlane				= $flight->forward->plane;
			$request->flightToClass				= $flight->forward->class;

			$request->flightFromNumber				= $flight->backward->flightnum;
			$request->flightFromDepartureDate		= $flight->backward->flydate;
			$request->flightFromDepartureTime		= $flight->backward->timefrom;
			$request->flightFromDepartureTerminal	= $flight->backward->terminalfrom;
			$request->flightFromArrivalDate			= $flight->backward->flydate;
			$request->flightFromArrivalTime			= $flight->backward->timeto;
			$request->flightFromArrivalTerminal		= $flight->backward->terminalto;
			$request->flightFromCarrier				= $flight->backward->aircompanyrus;
			$request->flightFromPlane				= $flight->backward->plane;
			$request->flightFromClass				= $flight->backward->class;
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
			else
			{
				$response->setJsonContent(['res' => '/' ]);
			}

			//Отправляем email
			$emailController = new EmailController();
			$emailController->sendRequest($type, $request);
		}
		else
		{
			$response->setJsonContent(['error' => 'save error' ]);
		}

		return $response;
    }

	public function findTourAction()
	{
		$response = new Response();
		$data = json_decode($_POST['data']);

		$emailController = new EmailController();
		$emailController->sendFindTour($data);
		$response->setJsonContent(['status' => 'ok' ]);

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
		', \Phalcon\Db::FETCH_OBJ);

		$regions = [];
		foreach($dbRegions as $region)
		{
			$region->country = $region->countryId;
			$region->country_name = $region->country_name;
			$regions[] = $region;
		}

		$response->setJsonContent([
			'countries'	=> $countries,
			'regions'	=> $regions
		]);

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

		return $response;
	}


	public function resultsAction($tourvisorId)
    {
	    $response = new Response();
	    
		$params = [
			'requestid'		=> $tourvisorId,
			'type'			=> 'result'
		];
		
		if(isset($_GET['page'])) $params['page'] = $_GET['page'];
		if(isset($_GET['limit'])) $params['onpage'] = $_GET['limit'];
		
		$result = Utils\Tourvisor::getMethod('result', $params);
		
		$res = new stdClass();
		
		$hotels = [];
		
		$res->status = $result->data->status;
		
		if(!empty($result->data->result->hotel))
		{
			foreach($result->data->result->hotel as $hotel)
			{
				$resultHotel = new stdClass();
				$resultHotel->id = $hotel->hotelcode;
				$resultHotel->name = $hotel->hotelname;
				$resultHotel->stars = $hotel->hotelstars;
				$resultHotel->rating = $hotel->hotelrating;
				if($hotel->isdescription) 
					$resultHotel->description = $hotel->hoteldescription;
				if($hotel->isphoto) 
					$resultHotel->image = $hotel->picturelink;
				$resultHotel->price = $hotel->price;
				
				$resultHotel->country = new stdClass();
				$resultHotel->country->id = $hotel->countrycode;
				$resultHotel->country->name = $hotel->countryname;
				
				$resultHotel->region = new stdClass();
				$resultHotel->region->id = $hotel->regioncode;
				$resultHotel->region->name = $hotel->regionname;
				$resultHotel->tours = $hotel->tours->tour;

				$urlName = str_ireplace([' ','&'], ['_','And'], ucwords(strtolower($resultHotel->name)));
				$resultHotel->hotelLink = "/hotel/" . $urlName . "-" . $resultHotel->id;
				
				$hotels[$hotel->hotelcode] = $resultHotel;	
			}
			
			$hotelIds = array_keys($hotels);
			
			if(!empty($hotelIds))
			{			
				$dbHotels = $this->modelsManager->createBuilder()
					->from('Models\Tourvisor\Hotels')
					->inWhere('id', $hotelIds)
					->getQuery()
					->execute();
					
				foreach($dbHotels as $dbHotel)
				{
					$types = new stdClass();
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
	    
	    return $response;
    }
    
    public function statusAction($tourvisorId)
    {
	    $response = new Response();
	    
		$params = array(
			'requestid'		=> $tourvisorId,
			'type'			=> 'status'
		);
		$result = Utils\Tourvisor::getMethod('result', $params);
		
		$res = new stdClass();
		$res->status = $result->data->status;
			    
	    $response->setJsonContent($res);
	    
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

		return $response;
	}

    public function tourAction($tourvisorId)
    {
	    $response = new Response();
	    
		$params = array(
			'tourid'		=> $tourvisorId,
			'flights'		=> 1
		);
		$result = Utils\Tourvisor::getMethod('actualize', $params);
	    
	    $response->setJsonContent($result);
	    
	    return $response;
    }

    public function tourDetailAction($tourvisorId)
    {
	    $response = new Response();

	    $detailData = Utils\Tourvisor::getMethod('actdetail', array(
		    'tourid' => $tourvisorId
	    ));

	    $response->setJsonContent($detailData);
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
		$result = Utils\Tourvisor::getMethod('hotel', $params);
	    
	    $response->setJsonContent($result);
	    
	    return $response;
    }
    
    public function regionsAction($countryId)
    {
	    $response = new Response();
	    
		$result = Tourvisor\Regions::find("countryId = $countryId");
	    
	    $response->setJsonContent($result->toArray());
	    
	    return $response;
    }
}