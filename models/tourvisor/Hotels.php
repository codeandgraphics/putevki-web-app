<?php

namespace Models\Tourvisor;

use Interfaces\ITourvisorEntity;
use Models\BaseModel;
use Phalcon\Mvc\Model;

class Hotels extends BaseModel implements ITourvisorEntity
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
		$this->belongsTo('countryId', Countries::name(), 'id', [
			'alias' => 'country'
		]);
		$this->belongsTo('regionId', Regions::name(), 'id', [
			'alias' => 'region'
		]);
		$this->belongsTo('starsId', Stars::name(), 'id', [
			'alias' => 'stars'
		]);

		$this->setSource('tourvisor_hotels');
	}

	public function fromTourvisor($item)
	{
		$this->id = $item->id;
		$this->name = $item->name;
		$this->starsId = $item->stars;
		$this->regionId = $item->region;
		$this->rating = $item->rating;

		$this->active = isset($item->active) ? (int)$item->active : 0;
		$this->relax = isset($item->relax) ? (int)$item->relax : 0;
		$this->family = isset($item->family) ? (int)$item->family : 0;
		$this->health = isset($item->health) ? (int)$item->health : 0;
		$this->city = isset($item->city) ? (int)$item->city : 0;
		$this->beach = isset($item->beach) ? (int)$item->beach : 0;
		$this->deluxe = isset($item->deluxe) ? (int)$item->deluxe : 0;
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

	/**
	 * @param mixed $parameters
	 * @return Hotels[]|Model\ResultsetInterface
	 */
	public static function find($parameters = null)
	{
		return parent::find($parameters);
	}

}