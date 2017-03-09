<?php

namespace Models\Tourvisor;

use Models\BaseModel as Model;

class Countries extends Model
{

	public $id;
	public $name;
	public $popular;

	public function initialize()
	{
		$this->setSource("tourvisor_countries");
	}

	public function format()
	{
		$country = new \stdClass();

		$country->id = $this->id;
		$country->name = $this->name;

		return $country;
	}

}
