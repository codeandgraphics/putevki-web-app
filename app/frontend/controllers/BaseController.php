<?php

namespace Frontend\Controllers;

use Frontend\Models\Params;
use Models\Countries;
use Models\Meta;
use Models\SearchQuery;
use Models\Tourvisor;
use Models\StoredQueries;
use Models\Tourvisor\Departures;
use Phalcon\Mvc\Controller;
use Models\Cities;
use Models\Branches;

class BaseController extends Controller
{
	public $params;
	public $city;
	public $departure;
	public $cities;
	public $branches;
	public $formCountries = '';
	public $formRegions = '';
	public $lastQueries = [];

	public function initialize()
	{
		if (array_key_exists(SearchQuery::LAST_QUERIES_KEY, $_COOKIE)) {
			$this->lastQueries = json_decode($_COOKIE[SearchQuery::LAST_QUERIES_KEY]);
		}

		$this->params = Params::getInstance();

		$this->cities = Cities::find(['active = 1', 'order' => 'main DESC, name']);
		$this->branches = Branches::findPublic([
			'active = 1'
		]);

		$popularCountries = StoredQueries::popularCountries();

		foreach ($popularCountries as $country) {
			$this->formCountries .= ',' . $country->name;
		}

		$popularRegions = StoredQueries::popularRegions();
		foreach ($popularRegions as $region) {
			$this->formRegions .= ',' . $region->name;
		}

		$builder = $this->modelsManager->createBuilder()
			->columns([
				'country.*',
				'tourvisor.*'
			])
			->addFrom(Countries::name(), 'country')
			->join(Tourvisor\Countries::name(), 'country.tourvisorId = tourvisor.id', 'tourvisor')
			->where('country.active = 1')
			->orderBy('tourvisor.name');

		$countries = $builder->getQuery()->execute();

		$this->city = Cities::findFirst('id=' . $this->params->city);
		$this->departure = Departures::findFirst('id=' . $this->params->search->from);

		$this->view->setVars([
			'branches' => $this->branches->toArray(),
			'cities' => $this->cities->toArray(),
			'city' => $this->city,
			'formRegions' => $this->formRegions,
			'formCountries' => $this->formCountries,
			'lastQueries' => $this->lastQueries,
			'countries' => $countries,
            'meta' => new Meta('', ''),
		]);
	}
}
