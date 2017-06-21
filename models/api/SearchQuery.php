<?php
namespace Models\Api;

use Frontend\Models\SearchParams;
use Models\Api\Entities\Filters;
use Models\Api\Entities\People;
use Models\Api\Entities\When;
use Models\Api\Entities\Where;
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
		if($params) {

			$this->from = (int) $params->from;

			$this->where = new Where($params->where);

			$this->when = new When($params->when);

			$this->people = new People($params->people);

			$this->filters = new Filters($params->filters);
		}
	}

    /**
     * @param $params SearchParams
     */
    public function fromParams($params) {
	    $this->from = $params->from;
	    $this->where = $params->where;
	    $this->when = $params->when;
	    $this->people = $params->people;
	    $this->filters = $params->filters;
    }

	public function run()
	{
		$existed = MobileSearchQuery::checkExists($this);

		// $existed = false;
        var_dump($this);

		if(!$existed) {
			$response = Tourvisor::getMethod('search', $this->buildTourvisorQuery());
			if(property_exists($response, 'result') && property_exists($response->result, 'requestid')) {

				$query = new MobileSearchQuery();
				$query->query = json_encode($this);
				$query->searchId = $response->result->requestid;
				$query->date = date('Y-m-d H:i:s');
				$query->save();

				return $query->searchId;
			}
			return false;
		}

		return $existed;
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
			'rating'		=> $this->filters->rating,
			'stars'         => $this->filters->stars,
			'starsbetter'   => 1,
			'meal'          => $this->filters->meal,
			'mealbetter'    => 1
		);

		if(count($this->where->regions) > 0)
		{
			$query['regions'] = implode(',', $this->where->regions);
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
				$query['rating'],
				$query['starsbetter']
			);
		}

		return $query;
	}
}