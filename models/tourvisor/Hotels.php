<?php

namespace Models\Tourvisor;

use Models\BaseModel;
use Phalcon\Mvc\Model;

class Hotels extends BaseModel
{

	public $id;
	public $name;
	public $starsId;
	public $regionId;
	public $countryId;
	public $rating;

	public $active;
	public $relax;
	public $family;
	public $health;
	public $city;
	public $beach;
	public $deluxe;

	public function initialize()
	{
		$this->belongsTo('countryId', 'Models\Tourvisor\Countries', 'id', array(
			'alias' => 'country'
		));
		$this->belongsTo('regionId', 'Models\Tourvisor\Regions', 'id', array(
			'alias' => 'region'
		));
		$this->belongsTo('starsId', 'Models\Tourvisor\Stars', 'id', array(
			'alias' => 'stars'
		));

		$this->setSource("tourvisor_hotels");
	}

	public function format()
	{
		$hotel = new \stdClass();

		$hotel->id = $this->id;
		$hotel->name = $this->name;
		$hotel->stars = $this->stars->format();
		$hotel->region = $this->region->format();
		$hotel->country = $this->country->format();
		$hotel->rating = $this->rating;

		$hotel->active = $this->active;
		$hotel->relax = $this->relax;
		$hotel->family = $this->family;
		$hotel->health = $this->health;
		$hotel->city = $this->city;
		$hotel->beach = $this->beach;
		$hotel->deluxe = $this->deluxe;

		return $hotel;
	}

	/**
	 * @param null $parameters
	 * @return Hotels|Model
	 */
	public static function findFirst($parameters = null)
	{
		return parent::findFirst($parameters);
	}

}