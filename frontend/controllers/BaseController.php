<?php

namespace Frontend\Controllers;

use Frontend\Models\Params;
use Models\Tourvisor\Countries;
use Models\Tourvisor\Departures;
use Phalcon\Mvc\Controller;
use Models\Cities;
use Models\Branches;
use Frontend\Models\SearchQueries;

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
		if(array_key_exists('lastQueries', $_COOKIE))
		{
			$queries = json_decode($_COOKIE['lastQueries']);
			if($queries)
			{
				$this->lastQueries = array_reverse($queries);
			}
		}

		$this->params = Params::getInstance();

		$this->cities = Cities::find(['active = 1','order' => 'main DESC, name']);
		$this->branches = Branches::find('active = 1');

		$countriesQuery = $this->db->query('
			SELECT COUNT(s.id) as queries, c.name FROM search_queries AS s
			INNER JOIN tourvisor_countries AS c ON s.countryId = c.id AND c.active = 1
			WHERE s.queryDate > (NOW() - INTERVAL 1 MONTH)
			GROUP BY s.countryId
			ORDER BY queries DESC
			LIMIT 3
		');

		$countries = $countriesQuery->fetchAll();
		foreach($countries as $country)
		{
			$this->formCountries[] = $country['name'];
		}
		$this->formCountries = implode(',', $this->formCountries);

		$regionsQuery = $this->db->query('
			SELECT COUNT(s.id) as queries, r.name FROM search_queries AS s
			INNER JOIN tourvisor_regions AS r ON s.regionId = r.id
            INNER JOIN tourvisor_countries AS c ON s.countryId = c.id AND c.active = 1
			WHERE s.queryDate > (NOW() - INTERVAL 1 MONTH)
			GROUP BY s.regionId
			ORDER BY queries DESC
			LIMIT 3
		');

		$regions = $regionsQuery->fetchAll();
		foreach($regions as $region)
		{
			$this->formRegions[] = $region['name'];
		}
		$this->formRegions = implode(',', $this->formRegions);

		$countries = Countries::find([
			'active = 1',
			'order' => 'name'
		]);

		$this->city = Cities::findFirst('id=' . $this->params->city);
		$this->departure = Departures::findFirst('id='.$this->params->search->from);

		$this->view->setVars([
			'branches'			=> $this->branches->toArray(),
			'cities'			=> $this->cities->toArray(),
			'city'		        => $this->city,
			'departure'         => $this->departure,
			'formRegions'		=> $this->formRegions,
			'formCountries'		=> $this->formCountries,
			'lastQueries'		=> $this->lastQueries,
			'countries'         => $countries
		]);
	}
}
