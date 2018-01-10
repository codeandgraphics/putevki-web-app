<?php

namespace Models;

use Frontend\Models\SearchParams;
use Models\Entities\Filters;
use Models\Entities\People;
use Models\Entities\When;
use Models\Entities\Where;
use Models\Tourvisor\Countries;
use Models\Tourvisor\Departures;
use Models\Tourvisor\Hotels;
use Models\Tourvisor\Meals;
use Models\Tourvisor\Regions;
use Models\Tourvisor\Stars;
use Phalcon\Di;
use Utils\Text;
use Utils\Tourvisor;

class SearchQuery
{
	const DELAY_TIME = 600;

	const LAST_QUERIES_KEY = 'last-queries';

	public $from;
	public $where;
	public $when;
	public $people;
	public $filters;

	public function __construct($params = null)
	{
		if ($params) {

			$this->from = (int)$params->from;

			$this->where = new Where($params->where);

			$this->when = new When($params->when);

			$this->people = new People($params->people);

			$this->filters = new Filters($params->filters);
		}
	}

	/**
	 * @param $params SearchParams
	 */
	public function fromParams($params)
	{
		$this->from = $params->from;
		$this->where = $params->where;
		$this->when = $params->when;
		$this->people = $params->people;
		$this->filters = $params->filters;
	}

	public function run($origin = Origin::WEB)
	{
		$existed = StoredQueries::checkExists($this);

		if (!$existed) {
			$response = Tourvisor::getMethod('search', $this->buildTourvisorQuery());
			if (property_exists($response, 'result') && property_exists($response->result, 'requestid')) {

				$searchId = $response->result->requestid;

				$this->addLastQuery();

				StoredQueries::store($this, $searchId, $origin);

				return $searchId;
			}
			return false;
		}

		return $existed;
	}

	public function addLastQuery() {
		$lastQueries = [];

		if (array_key_exists(self::LAST_QUERIES_KEY, $_COOKIE)) {
			$lastQueries = json_decode($_COOKIE[self::LAST_QUERIES_KEY]);
		}

		$lastQueries = array_slice($lastQueries,0, 2);
		array_unshift($lastQueries, $this->buildHumanizedQuery());

		$cookieTimeout = Di::getDefault()->get('config')->common->cookieTimeout;
		setcookie(self::LAST_QUERIES_KEY, json_encode($lastQueries), time() + $cookieTimeout, '/');
	}

	public function buildTourvisorQuery()
	{
		$query = array(
			'departure' => $this->from,
			'country' => $this->where->country,
			'adults' => $this->people->adults,
			'datefrom' => $this->when->dateFrom,
			'dateto' => $this->when->dateTo,
			'nightsfrom' => $this->when->nightsFrom,
			'nightsto' => $this->when->nightsTo,
			'rating' => $this->filters->rating,
			'stars' => $this->filters->stars,
			'starsbetter' => 1,
			'meal' => $this->filters->meal,
			'mealbetter' => 1
		);

		if (count($this->where->regions) > 0) {
			$query['regions'] = implode(',', $this->where->regions);
		}

		$childrenCount = is_array($this->people->children) ? count($this->people->children) : 0;

		if ($childrenCount > 0) {
			$query['child'] = $childrenCount;
			if (is_array($this->people->children)) {
				foreach ($this->people->children as $key => $value) {
					$query['childage' . ($key + 1)] = $value;
				}
			}
		}

		if ($this->where->hotels > 0) {
			$query['hotels'] = $this->where->hotels;

			unset(
				$query['rating'],
				$query['starsbetter']
			);
		}

		return $query;
	}

	public function buildHumanizedQuery()
	{
		$modelsManager = Di::getDefault()->get('modelsManager');

		$bind = [
			'departure' => $this->from,
			'country'   => $this->where->country,
			'region'    => is_array($this->where->regions) && array_key_exists(0, $this->where->regions) ?
				$this->where->regions[0] : 0,
			'hotel'     => $this->where->hotels,
			'meal'      => $this->filters->meal,
			'stars'     => $this->filters->stars
		];

		$builder = $modelsManager->createBuilder()
			->columns([
				'departure.name AS departureName',
				'country.name AS countryName',
				'region.name AS regionName',
				'hotel.name AS hotelName',
				'meal.name AS mealName',
				'stars.name AS starsName'
			])
			->addFrom(Departures::name(), 'departure')
			->leftJoin(Countries::name(), 'country.id = :country:', 'country')
			->leftJoin(Regions::name(), 'region.id = :region:', 'region')
			->leftJoin(Hotels::name(), 'hotel.id = :hotel:', 'hotel')
			->leftJoin(Meals::name(), 'meal.id = :meal:', 'meal')
			->leftJoin(Stars::name(), 'stars.id = :stars:', 'stars')
			->where('departure.id = :departure:');

		$info = $builder->getQuery()->getSingleResult($bind);

		$queryString = $info->departureName . ' — ';

		if ($info->countryName) {
			$queryString .= $info->countryName;
		}

		if ($info->regionName) {
			$queryString .= ' (' . $info->regionName . ')';
		}

		if ($info->hotelName) {
			$queryString .= ' ' . $info->hotelName . '';
		}

		$queryString .= ', вылет ';
		$queryString .= $this->when->isDateRange() ? $this->when->notRangeDate() : $this->when->dateFrom;
		$queryString .= $this->when->isDateRange() ? ' (±2 дня)' : '';

		$queryString .= ' на ';
		$nights = $this->when->isNightsRange() ? $this->when->notRangeNights() : $this->when->nightsFrom;
		$queryString .= Text::humanize('nights', $nights);
		$queryString .= $this->when->isNightsRange() ? ' (±2 ночи)' : '';

		$queryString .= ', ' . $this->people->adults . ' ' . Text::humanize('adults', $this->people->adults);


		if (is_array($this->people->children)) {
			$kidsCount = count($this->people->children);
			$queryString .= ', ' . $kidsCount . ' ' . Text::humanize('kids', $kidsCount);
		}

		$queryString .= ', ' . $info->starsName . ' звезд и выше';
		$queryString .= ', ' . $info->mealName;

		return $queryString;

	}
}