<?php
namespace Models\Api\Entities;

use Models\Tourvisor\Countries;

class Country {
	public $id;
	public $name;
	public $popular;
	public $regions = [];

	public function __construct(Countries $country = null)
	{
		if($country) {
			$this->id = (int) $country->id;
			$this->name = $country->name;
			$this->popular = (int) $country->popular;
		}
	}
}