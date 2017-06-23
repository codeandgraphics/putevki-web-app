<?php

namespace Models;

use Frontend\Models\SearchParams;
use Models\Entities\Filters;
use Models\Entities\People;
use Models\Entities\When;
use Models\Entities\Where;
use Utils\Tourvisor;

class SearchQuery
{
	const DELAY_TIME = 600;

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

	public function run()
	{
		$existed = StoredQueries::checkExists($this);

		if (!$existed) {
			$response = Tourvisor::getMethod('search', $this->buildTourvisorQuery());
			if (property_exists($response, 'result') && property_exists($response->result, 'requestid')) {

				$searchId = $response->result->requestid;

				StoredQueries::store($this, $searchId);

				return $searchId;
			}
			return false;
		}

		return $existed;
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

		$childrenCount = count($this->people->children);
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
		$queryString = $this->departure->name . ' — ';

		if ($this->countryId) {
			$queryString .= $this->country->name;
		}

		if ($this->regionId) {
			$queryString .= ' (' . $this->region->name . ')';
		}

		if ($this->hotelId) {
			$queryString .= ' ' . $this->hotel->name . '';
		}

		$queryString .= ', ' . implode('.', array_reverse(explode('-', $this->date))); //Хз что быстрее, strtotime или это
		$queryString .= $this->date_range ? ' (±2 дня)' : '';

		$queryString .= ', ' . Text::humanize('nights', $this->nights);
		$queryString .= $this->nights_range ? ' (±2 ночи)' : '';

		$queryString .= ', ' . $this->adults . ' ' . Text::humanize('adults', $this->adults);

		$kidsCount = substr_count($this->kids, '+') + 1; // count(explode('+',$this->kids));

		if ($kidsCount > 0) {
			$queryString .= ', ' . $kidsCount . ' ' . Text::humanize('kids', $kidsCount);
		}

		$queryString .= ', ' . $this->starsId . ' звезд и выше';
		$queryString .= ', ' . $this->meal->name;

		return $queryString;

	}
}