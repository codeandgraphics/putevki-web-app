<?php

use Phalcon\Http\Response as Response;

use Models\Tourvisor as Tourvisor;
use Phalcon\Cache\Backend\File as Cache,
	Phalcon\Cache\Frontend\Data as CacheData;

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
	}

	public function indexAction()
	{
		$response = new Response();

		$data = [];

		$response->setJsonContent($data);

		return $response;
	}

	public function dictionariesAction() {
		$response = new Response();
		$data = [
			'departures' => [],
			'destinations' => []
		];

		$data['departures'] = \Models\Tourvisor\Departures::find()->toArray();

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

		$regionObject = new stdClass();

		foreach($items as $item) {
			$country = $item->country;
			$region = $item->region;
			$countryId = (int) $region->countryId;

			if(!array_key_exists($countryId, $data['destinations'])) {

				$countryObject = new stdClass();
				$countryObject->id = $countryId;
				$countryObject->name = $country->name;
				$countryObject->popular = (int) $country->popular;
				$countryObject->regions = [];

				$data['destinations'][$countryId] = $countryObject;
			}

			$regionObjectClone = clone $regionObject;
			$regionObjectClone->id = (int) $region->id;
			$regionObjectClone->name = $region->name;
			$regionObjectClone->popular = (int) $region->popular;

			$data['destinations'][$region->countryId]->regions[] = $regionObjectClone;
		}

		$data['destinations'] = array_values($data['destinations']);

		$response->setJsonContent($data);
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