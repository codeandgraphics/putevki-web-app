<?php

namespace Frontend\Controllers;

use Backend\Models\Requests;
use Models\Branches;
use Models\Cities;
use Models\Countries;
use Models\Regions;
use Models\Tourvisor;
use Phalcon\Cache\Backend\File as Cache;
use Phalcon\Cache\Frontend\Data as CacheData;

use Backend\Controllers\EmailController;
use Models\Api\Error;
use Models\Api\JSONResponse;
use Models\SearchQuery;
use Models\Entities;

use Phalcon\Security;
use Utils\Tourvisor as TourvisorUtils;

class ApiController extends BaseController
{
	protected $_cache;
	protected $_KEY = 'u67x9raC(Y|Mt;R|?3+1y|Vv:O|}5>r/JwBLtE>,E+y-Z>Hnf_J<<9.rkrbv~dMF';
	const DATE_TIME_FORMAT = 'Y-m-d H:i:s';

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
	}

	public function onConstruct() {
	    $this->view->disable();
    }

	public function beforeExecuteRoute($dispatcher) {
	    //Need to fix this
	    $isLocal = $this->request->getHeader('X-Requested-With') === 'XMLHttpRequest';

	    if($isLocal) return true;

        $clientSign = $this->request->getHeader('X-Request-Sign');
        $clientDate = (int) $this->request->getHeader('X-Request-Time');

        if(!$clientSign || !$clientDate) {
            $response = new JSONResponse(Error::API_CREDENTIALS_MISSED);
            echo $response->getContent();
            return false;
        }

        $body = $this->request->getJsonRawBody();

        $body->date = $clientDate;

        $string = json_encode($body);

        $sec = new Security();
        $serverSign = $sec->computeHmac($string, $this->_KEY, 'sha512');

        $isExpired = abs(time() - $clientDate) > 300;

        if($serverSign !== $clientSign || $isExpired){
            $response = new JSONResponse(Error::API_AUTH_ERROR);
            echo $response->getContent();
            return false;
        }

        return true;
    }

	public function indexAction() : JSONResponse
	{
		return new JSONResponse(Error::NO_ERROR);
	}

	public function requestAction()
	{
		if ($this->request->isPost()) {

			$data = $this->request->getJsonRawBody();

			$request = new Requests();

			$order = $data->order;

			switch ($order->origin) {
				case Requests::ORIGIN_IOS:
					$request->origin = Requests::ORIGIN_IOS;
					break;
				case Requests::ORIGIN_ANDROID:
					$request->origin = Requests::ORIGIN_ANDROID;
					break;
				default:
					$request->origin = Requests::ORIGIN_MOBILE;
					break;
			}

			//Клиент
			$request->subjectName = $order->subject->name;
			$request->subjectPhone = $order->subject->phone;
			$request->subjectEmail = $order->subject->email;

			//Данные тура
			$request->setHotel($order->hotel);

			if ($order->flights) {
				$request->setFlights('To', $order->flights->to);
				$request->setFlights('From', $order->flights->from);
			}

			$request->tourOperatorId = $order->tour->operator;
			$request->tourOperatorLink = $order->tour->operatorLink;
			$request->price = $order->tour->price;
			$request->departureId = $order->tour->from;

			if ($request->save()) {

				//Отправляем email
				$emailController = new EmailController();
				$emailController->sendRequest('app', $request->id);
				$emailController->sendAdminNotification($request->id);

				return new JSONResponse(Error::NO_ERROR, ['success' => true]);
			}

			return new JSONResponse(Error::API_ERROR);
		}
	}

	public function dictionariesAction() : JSONResponse
	{
		$response = array(
			'cities' => [],
			'departures' => [],
			'destinations' => [],
			'stars' => [],
			'meal' => [],
			'rating' => [
				new Entities\Rating(0, 'Любой'),
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
			->addFrom(Cities::name(), 'city')
			->join(
				Branches::name(),
				'branch.cityId = city.id',
				'branch'
			)
			->orderBy('city.main DESC, city.name')
			->where('city.active = 1 AND branch.active = 1');

		$cityItems = $citiesBuilder->getQuery()->execute();

		$cities = [];
		foreach ($cityItems as $item) {
			if (!array_key_exists($item->city->id, $cities)) {
				$cities[$item->city->id] = new Entities\City($item->city);
			}

			$cities[$item->city->id]->offices[] = new Entities\Office($item->branch);
		}

		$response['cities'] = array_values($cities);

		/* Departures */
		$departures = Tourvisor\Departures::find();

		foreach ($departures as $departure) {
			$response['departures'][] = new Entities\Departure($departure);
		}

		/* Destinations */
		$builder = $this->modelsManager->createBuilder()
			->columns([
				'country.*',
				'region.*',
				'tourvisorRegion.*',
				'tourvisorCountry.*'
			])
			->addFrom(Countries::name(), 'country')
			->innerJoin(
				Tourvisor\Countries::name(),
				'tourvisorCountry.id = country.tourvisorId',
				'tourvisorCountry')
			->innerJoin(
				Tourvisor\Regions::name(),
				'tourvisorRegion.countryId = tourvisorCountry.id',
				'tourvisorRegion'
			)
			->innerJoin(
				Regions::name(),
				'region.tourvisorId = tourvisorRegion.id',
				'region'
			)
			->where('country.active = 1 AND region.active = 1')
			->orderBy('country.popular DESC, tourvisorCountry.name, region.popular DESC, tourvisorRegion.name');

		$items = $builder->getQuery()->execute();

		foreach ($items as $item) {
			$country = $item->country;
			$region = $item->region;
			$tourvisorCountry = $item->tourvisorCountry;
			$tourvisorRegion = $item->tourvisorRegion;
			$countryId = (int)$tourvisorRegion->countryId;

			if (!array_key_exists($countryId, $response['destinations'])) {
				$response['destinations'][$countryId] = new Entities\Country($country, $tourvisorCountry);
			}

			$response['destinations'][$countryId]->regions[] = new Entities\Region($region, $tourvisorRegion);
		}

		$response['destinations'] = array_values($response['destinations']);

		$meals = Tourvisor\Meals::find();
		foreach ($meals as $meal) {
			$response['meal'][] = new Entities\Meal($meal);
		}

		$stars = Tourvisor\Stars::find();
		foreach ($stars as $star) {
			$response['stars'][] = new Entities\Star($star);
		}

		return new JSONResponse(Error::NO_ERROR, $response);
	}

	public function initSearchAction() : JSONResponse
	{
		$body = $this->request->getJsonRawBody();

		$query = new SearchQuery($body->params);

		$searchId = $query->run();

		if ($searchId) {
			return new JSONResponse(Error::NO_ERROR, ['searchId' => $searchId]);
		}

		return new JSONResponse(Error::API_ERROR);
	}

	public function initSearchSignedAction() : JSONResponse
	{
		$body = $this->request->getJsonRawBody();

		$params = $body->params;

        $query = new SearchQuery($params);

        $searchId = $query->run();

        if ($searchId) {
            return new JSONResponse(Error::NO_ERROR, ['searchId' => $searchId]);
        }

        return new JSONResponse(Error::API_ERROR);
    }

	public function searchStatusAction() : JSONResponse
	{
		$searchId = $this->request->get('searchId');

		$params = array(
			'requestid' => $searchId,
			'type' => 'status'
		);

		$result = TourvisorUtils::getMethod('result', $params);

		if (property_exists($result, 'data') && property_exists($result->data, 'status')) {
			return new JSONResponse(Error::NO_ERROR, ['status' => new Entities\Status($result->data->status)]);
		}

		return new JSONResponse(Error::API_ERROR);
	}

	public function searchResultAction()
	{
		$searchId = $this->request->get('searchId');
        $onPage = $this->request->get('limit') ? : false;
		$page = $this->request->get('page') ? : false;

		$params = array(
			'requestid' => $searchId,
			'type' => 'result'
		);

		if($onPage) {
		    $params['onpage'] = $onPage;
        }
        if($page) {
		    $params['page'] = $page;
        }

		$result = TourvisorUtils::getMethod('result', $params);

		if (
			property_exists($result, 'data') &&
			property_exists($result->data, 'status')
		) {

			$status = new Entities\Status($result->data->status);
			$hotels = [];

			if (property_exists($result->data, 'result')) {
				foreach ($result->data->result->hotel as $item) {
				    $hotel = new Entities\Hotel($item);
				    $hotelIds[] = $hotel->id;
					$hotels[] = $hotel;
				}
			}

            if (!empty($hotelIds)) {
                $items = $this->modelsManager->createBuilder()
                    ->from(Tourvisor\Hotels::name())
                    ->inWhere('id', $hotelIds)
                    ->getQuery()
                    ->execute();

                $hotelTypes = [];

                foreach ($items as $item) {
                    $hotelTypes[$item->id] = Entities\HotelTypes::fromHotel($item);
                }

               $hotels = array_map(function($item) use ($hotelTypes) {
                    if (array_key_exists($item->id, $hotelTypes)) {
                        $item->types = $hotelTypes[$item->id];
                    }
                    return $item;
                }, $hotels);
            }

			return new JSONResponse(Error::NO_ERROR, ['status' => $status, 'hotels' => $hotels, 'typesMask' => Entities\HotelTypes::getMask()]);
		}

		return new JSONResponse(Error::API_ERROR);
	}

	public function fullSearchResultAction() : JSONResponse
	{

		$searchId = $this->request->get('searchId');

		$params = array(
			'requestid' => $searchId,
			'type' => 'result',
			'onpage' => 999
		);

		$result = TourvisorUtils::getMethod('result', $params);

		if (
			property_exists($result, 'data') &&
			property_exists($result->data, 'status')
		) {

			$status = new Entities\Status($result->data->status);
			$hotels = [];

			if (property_exists($result->data, 'result')) {
				foreach ($result->data->result->hotel as $hotel) {
					$hotels[] = new Entities\Hotel($hotel);
				}
			}

			return new JSONResponse(Error::NO_ERROR, ['status' => $status, 'hotels' => $hotels]);
		}

		return new JSONResponse(Error::API_ERROR);
	}

	public function actualizeTourAction() : JSONResponse
	{
		$tourId = $this->request->get('tourId');

		$params = array(
			'tourid' => $tourId
		);

		$actdetail = TourvisorUtils::getMethod('actdetail', $params);
		$actualize = TourvisorUtils::getMethod('actualize', $params);


		return new JSONResponse(Error::NO_ERROR, [
			'details' => new Entities\TourDetails($actdetail, $actualize->data->tour),
		]);
	}

	public function hotelAction() : JSONResponse
	{

		$hotelId = $this->request->get('hotelId');

		$params = array(
			'hotelcode' => $hotelId,
			'removetags' => 1,
			'reviews' => 1
		);

		$result = TourvisorUtils::getMethod('hotel', $params);

		if (
			property_exists($result, 'data') &&
			property_exists($result->data, 'hotel')
		) {
			$hotel = new Entities\HotelFull($result->data->hotel);
			$hotel->id = (int)$hotelId;
			return new JSONResponse(Error::NO_ERROR, ['hotel' => $hotel]);
		}

		return new JSONResponse(Error::API_ERROR);
	}

	public function hotelsAction() : JSONResponse
	{
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
				->limit(20);

			$dbHotels = $builder->getQuery()->execute(['query' => '%' . $query . '%']);

			foreach ($dbHotels as $hotel) {
				$hotels[] = new Entities\SearchHotel($hotel);
			}
		}

		return new JSONResponse(Error::NO_ERROR, ['hotels' => $hotels]);
	}
}