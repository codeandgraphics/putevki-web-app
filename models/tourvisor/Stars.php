<?php

namespace Models\Tourvisor;

use \Phalcon\Mvc\Model as Model;

class Stars extends Model
{

	public $id;
	public $name;

	public function initialize()
	{
		$this->setSource("tourvisor_stars");
	}

	public function format()
	{
		$star = new \stdClass();

		$star->id = $this->id;
		$star->name = $this->name;

		return $star;
	}

}