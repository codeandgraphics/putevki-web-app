<?php

namespace Models\Tourvisor;

use Models\BaseModel;
use Phalcon\Mvc\Model;

class Countries extends BaseModel
{

	public $id;
	public $name;
	public $popular;

	public function initialize()
	{
		$this->hasMany('id', Regions::name(), 'countryId', [
			'alias' => 'regions'
		]);

		$this->setSource('tourvisor_countries');
	}

	public function format()
	{
		$country = new \stdClass();

		$country->id = $this->id;
		$country->name = $this->name;

		return $country;
	}

	/**
	 * @param null $parameters
	 * @return Countries|Model
	 */
	public static function findFirst($parameters = null)
	{
		return parent::findFirst($parameters);
	}

}
