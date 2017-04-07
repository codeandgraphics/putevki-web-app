<?php

namespace Models\Tourvisor;

use Models\BaseModel as Model;

class Regions extends Model
{

	public $id;
	public $name;
	public $countryId;
	public $popular;

	public function initialize()
	{
		$this->belongsTo('countryId', 'Models\Tourvisor\Countries', 'id', array(
			'alias' => 'country'
		));

		$this->setSource('tourvisor_regions');
	}

	public function format()
	{
		$region = new \stdClass();

		$region->id = $this->id;
		$region->name = $this->name;
		$region->country = $this->country->format();

		return $region;
	}
}