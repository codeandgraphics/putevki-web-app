<?php
namespace Models\Api;

use Models\Api\Entities\People;
use Models\Api\Entities\When;
use Models\Api\Entities\Where;
use Utils\Tourvisor;

class SearchQuery
{
	public $from;
	public $where;
	public $when;
	public $people;

	public function __construct($params = null)
	{
		if($params) {

			$this->from = (int) $params->from;

			$this->where = new Where($params->where);

			$this->when = new When($params->when);

			$this->people = new People($params->people);
		}
	}

	public function run()
	{
		$response = Tourvisor::getMethod('search', $this->buildTourvisorQuery());
		if(property_exists($response, 'result') && property_exists($response->result, 'requestid')) {
			return $response->result->requestid;
		} else {
			return false;
		}
	}

	public function buildTourvisorQuery()
	{
		$query = array(
			'departure'		=> $this->from,
			'country'		=> $this->where->country,
			'adults'		=> $this->people->adults,
			'datefrom'      => $this->when->dateFrom,
			'dateto'        => $this->when->dateTo,
			'nightsfrom'    => $this->when->nightsFrom,
			'nightsto'      => $this->when->nightsTo,
			'rating'		=> 3
		);

		if(count($this->where->regions) > 0)
		{
			$query['regions'] = $this->where->regions;
		}

		$childCount = count($this->people->children);
		if($childCount > 0)
		{
			$query['child'] = $childCount;
			foreach($this->people->children as $key=>$value)
			{
				$query['childage'.($key+1)] = $value;
			}
		}

		if(count($this->where->hotels) > 0)
		{
			$query['hotels'] = $this->where->hotels;

			unset(
				$query['rating']
			);
		}

		return $query;
	}
}