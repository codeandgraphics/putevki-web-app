<?php

namespace Models\Tourvisor;

use Models\BaseModel;
use Phalcon\Mvc\Model;

class Regions extends BaseModel
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

	/**
	 * @param null $parameters
	 * @return Regions|Model
	 */
	public static function findFirst($parameters = null)
	{
		return parent::findFirst($parameters);
	}
}