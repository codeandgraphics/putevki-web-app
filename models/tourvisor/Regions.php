<?php

namespace Models\Tourvisor;

use Interfaces\ITourvisorEntity;
use Models\BaseModel;
use Phalcon\Mvc\Model;

/**
 * Class Regions
 * @package Models\Tourvisor
 */
class Regions extends BaseModel implements ITourvisorEntity
{
	public $id;
	public $name;
	public $countryId;
	public $popular;

	public function initialize()
	{
		$this->belongsTo('countryId', Countries::name(), 'id', array(
			'alias' => 'country'
		));

		$this->setSource('tourvisor_regions');
	}

	public function fromTourvisor($item)
	{
		$this->id           = $item->id;
		$this->name         = $item->name;
		$this->countryId    = $item->country;
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

	/**
	 * @param mixed $parameters
	 * @return Regions[]|Model\ResultsetInterface
	 */
	public static function find($parameters = null)
	{
		return parent::find($parameters);
	}
}