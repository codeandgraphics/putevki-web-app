<?php

namespace Models\Tourvisor;

use Models\BaseModel;

class Stars extends BaseModel
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