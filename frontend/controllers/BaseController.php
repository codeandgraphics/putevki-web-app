<?php

namespace Frontend\Controllers;

use Frontend\Models\Params;
use Models\StoredQueries;
use Models\Tourvisor\Countries;
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
		// TODO: last queries
		if (array_key_exists('lastQueries', $_COOKIE)) {
			$queries = json_decode($_COOKIE['lastQueries']);
			if ($queries) {
				$this->lastQueries = array_reverse($queries);
			}
		}

		$this->params = Params::getInstance();

		$this->cities = Cities::find(['active = 1', 'order' => 'main DESC, name']);
		$this->branches = Branches::find('active = 1');

		$popularCountries = StoredQueries::popularCountries();

		foreach ($popularCountries as $country) {
			$this->formCountries .= ',' . $country->name;
		}

		$popularRegions = StoredQueries::popularRegions();
		foreach ($popularRegions as $region) {
			$this->formRegions .= ',' . $region->name;
		}

		$countries = Countries::find([
			'active = 1',
			'order' => 'name'
		]);

		$this->city = Cities::findFirst('id=' . $this->params->city);
		$this->departure = Departures::findFirst('id=' . $this->params->search->from);

		$this->view->setVars([
			'branches' => $this->branches->toArray(),
			'cities' => $this->cities->toArray(),
			'city' => $this->city,
			'formRegions' => $this->formRegions,
			'formCountries' => $this->formCountries,
			'lastQueries' => $this->lastQueries,
			'countries' => $countries
		]);
	}
}
