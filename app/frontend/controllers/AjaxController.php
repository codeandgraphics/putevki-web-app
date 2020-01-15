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

        $tour = $form->tour;

        $request = new Requests();

        $request->price = (int) $form->price;

        $request->origin = Requests::ORIGIN_WEB;

        //Клиент
        $request->subjectSurname = $form->person->surname;
        $request->subjectName = $form->person->name;
        $request->subjectPatronymic = $form->person->patronymic;
        $request->subjectPhone = $form->person->phone;
        $request->subjectEmail = $form->person->email;
        $request->comment = $form->comments;

        //Данные тура
        $hotel = new \stdClass();
        $hotel->name = $tour->hotelname;
        $hotel->country = $tour->countryname;
        $hotel->region = $tour->hotelregionname;
        $hotel->date = $tour->flydate;
        $hotel->nights = $tour->nights;
        $hotel->placement = $tour->placement;
        $hotel->meal = $tour->meal;
        $hotel->room = $tour->room;

        $request->setHotel($hotel);

        $flight = $form->flight;

        if ($flight) {
            $request->setFlights('To', $flight->forward);
            $request->setFlights('From', $flight->backward);
        }

        $request->tourOperatorId = $tour->operatorcode;
        $request->tourOperatorLink = $tour->operatorlink;
        $request->departureId = $tour->departurecode;

        $tourists = [];

        foreach ($form->tourists as $i => $formTourist) {
            $tourist = new \stdClass();

            $tourist->passportName = $formTourist->firstname;
            $tourist->passportSurname = $formTourist->lastname;
            $tourist->passportNumber = $formTourist->passport;
            $tourist->passportEndDate = $formTourist->end_date;
            $tourist->passportIssued = $formTourist->issue;
            $tourist->birthDate = $formTourist->birth;
            $tourist->gender = $formTourist->gender === 'man' ? 'm' : 'f';
            $tourist->visa =
                property_exists($formTourist, 'visa') &&
                $formTourist->visa === 'on'
                    ? 1
                    : 0;
            $tourist->nationality = $formTourist->nationality;

            $touristModel = Tourists::addOrUpdate($tourist);

            $tourists[] = $touristModel;
        }

        if ($type === 'office') {
            $request->branchId = (int) $form->branch;
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
            } elseif ($type === 'office') {
                $mailController = new EmailController();
                $mailController->sendBranchNotification($request->id);
                $response->setJsonContent(['res' => '/']);
            } else {
                $response->setJsonContent(['res' => '/']);
            }

            //Отправляем email
            $emailController = new EmailController();
            $emailController->sendRequest($type, $request->id);
            $emailController->sendAdminNotification($request->id);
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
        $builder = $this->modelsManager
            ->createBuilder()
            ->columns(['country.*', 'region.*'])
            ->addFrom(Tourvisor\Countries::name(), 'country')
            ->join(
                Tourvisor\Regions::name(),
                'region.countryId = country.id',
                'region'
            )
            ->where('country.active = 1');

        $items = $builder->getQuery()->execute();

        $countries = [];
        $regions = [];

        foreach ($items as $item) {
            if (!array_key_exists($item->country->id, $countries)) {
                $country = new Entities\Country(null, $item->country);
                unset($country->regions, $country->popular, $country->visa);
                $countries[$country->id] = $country;
            }

            $region = new \stdClass();
            $region->id = (int) $item->region->id;
            $region->name = $item->region->name;
            $region->country = (int) $item->country->id;
            $region->countryName = $item->country->name;
            $regions[] = $region;
        }

        $departuresToCountries = [];

        $depItems = Tourvisor\DeparturesToCountries::find();

        foreach ($depItems as $depItem) {
            $departuresToCountries[(int) $depItem->departureId][] =
                (int) $depItem->countryId;
        }

        $response->setJsonContent([
            'countries' => array_values($countries),
            'regions' => $regions,
            'departuresToCountries' => $departuresToCountries
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
            $builder = $this->modelsManager
                ->createBuilder()
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

            $dbHotels = $builder
                ->getQuery()
                ->execute(['query' => '%' . $query . '%']);

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

        $formParams = (object) $this->request->get('params');

        $params = Params::getInstance();
        $params->search->fromSearchForm($formParams);

        $path = '/search/';

        if ($params->search->isHotelQuery()) {
            $path = '/search/hotel/';
        }

        $response->setJsonContent([
            'url' => $path . $params->search->buildQueryString()
        ]);

        $response->setHeader('Content-Type', 'application/json; charset=UTF-8');

        return $response;
    }

    public function searchHotelAction()
    {
        $response = new Response();

        $formParams = (object) $this->request->get('params');

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
            'items' => $items ?: 8
        ];

        if ($country) {
            $params['countries'] = $country;
        }
        if ($region) {
            $params['regions'] = $region;
        }

        $tours = TourvisorUtils::getMethod('hottours', $params);

        $response->setJsonContent($tours->hottours->tour);

        $response->setHeader('Content-Type', 'application/json; charset=UTF-8');

        return $response;
    }
}
