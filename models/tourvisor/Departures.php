<?php

namespace Models\Tourvisor;

use \Phalcon\Mvc\Model as Model;

class Departures extends Model
{

	public $id;
	public $name;
	public $name_from;

	public function initialize()
	{
		$this->setSource("tourvisor_departures");
	}

	public function format()
	{
		$departure = new \stdClass();

		$departure->id = $this->id;
		$departure->name = $this->name;
		$departure->name_from = $this->name_from;

		return $departure;
	}

}