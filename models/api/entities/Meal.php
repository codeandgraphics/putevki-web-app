<?php
namespace Models\Api\Entities;

use Models\Tourvisor\Meals;

class Meal {
	public $id;
	public $name;

	public function __construct(Meals $meal = null)
	{
		if($meal) {
			$this->id = (int) $meal->id;
			$this->name = $meal->russian;
		}
	}
}