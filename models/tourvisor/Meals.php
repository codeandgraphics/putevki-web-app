<?php

namespace Models\Tourvisor;

use \Phalcon\Mvc\Model as Model;

class Meals extends Model
{

	public $id;
	public $name;
	public $fullname;
	public $russian;
	public $russianfull;

	public function initialize()
	{
		$this->setSource("tourvisor_meals");
	}

	public function format()
	{
		$meal = new \stdClass();

		$meal->id = $this->id;
		$meal->name = $this->name;
		$meal->fullname = $this->fullname;
		$meal->russian = $this->russian;
		$meal->russianfull = $this->russianfull;

		return $meal;
	}

}