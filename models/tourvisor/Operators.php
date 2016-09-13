<?php

namespace Models\Tourvisor;

use \Phalcon\Mvc\Model as Model;

class Operators extends Model
{

	public $id;
	public $name;
	public $fullname;
	public $russian;
	public $onlinebooking;
	public $legal;
	public $guarantee;

	public function initialize()
	{
		$this->setSource("tourvisor_operators");
	}

	public function format()
	{
		$operator = new \stdClass();

		$operator->id = $this->id;
		$operator->name = $this->name;
		$operator->fullname = $this->fullname;
		$operator->russian = $this->russian;
		$operator->onlinebooking = $this->onlinebooking;
		$operator->legal = $this->legal;
		$operator->guarantee = $this->guarantee;

		return $operator;
	}

}