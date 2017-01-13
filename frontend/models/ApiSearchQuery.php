<?php

namespace Frontend\Models;

use Models\Tourvisor;

class ApiSearchQuery
{
	public $departure;
	public $country;

	public $regions;
	public $hotels;

	public $dateFrom;
	public $dateTo;

	public $nightsFrom;
	public $nightsTo;

	public $child = 0;
	public $childAges = [];

	const FLOATING_DAYS = 2;


	public function __construct($params = null)
	{
		if($params) {

			//From

			$this->departure = (int) $params->from;


			//Where

			$this->country = (int) $params->where->country;

			if(is_array($params->where->regions)) {
				$this->regions = implode(',', $params->where->regions);
			}

			if($params->where->hotel) {
				$this->hotels = $params->where->hotel->id;
			}


			//When

			$date = \DateTime::createFromFormat('Y-m-d', $params->when->date);

			if($params->when->floating) {
				$this->dateFrom = $date->modify('-' . self::FLOATING_DAYS . 'days')->format('d.m.Y');
				$this->dateTo = $date->modify('+' . (self::FLOATING_DAYS * 2) . 'days')->format('d.m.Y');
			} else {
				$this->dateFrom = $date->format('d.m.Y');
				$this->dateTo = $this->dateFrom;
			}

			list($this->nightsFrom, $this->nightsTo) = $params->when->nights;

			//People

			$this->adults = $params->people->adults;

			$kids = $params->people->kids;

			if($kids > 0) {
				$maxChildren = $this->child + $kids;
				for($i = $this->child; $i < $maxChildren; $i++){
					$this->childAges[] = 3;
				}
				$this->child = $maxChildren;
			}

			$babies = $params->people->babies;

			if($babies > 0) {
				$maxChildren = $this->child + $babies;
				for($i = $this->child; $i < $maxChildren; $i++){
					$this->childAges[] = 1;
				}
				$this->child = $maxChildren;
			}
		}
	}

	public function run()
	{
		return \Utils\Tourvisor::getMethod('search', $this->buildTourvisorQuery());
	}

	public function buildTourvisorQuery()
	{
		$query = array(
			'departure'		=> $this->departure,
			'country'		=> $this->country,
			'adults'		=> $this->adults,
			'datefrom'      => $this->dateFrom,
			'dateto'        => $this->dateTo,
			'nightsfrom'    => $this->nightsFrom,
			'nightsto'      => $this->nightsTo,
			'rating'		=> 3
		);


		if($this->regions)
		{
			$query['regions'] = $this->regions;
		}

		if($this->child > 0)
		{
			$query['child'] = $this->child;
			foreach($this->childAges as $key=>$value)
			{
				$query['childage'.($key+1)] = $value;
			}
		}

		if($this->hotels)
		{
			$query['hotels'] = $this->hotels;

			unset(
				$query['rating']
			);
		}

		return $query;
	}
}