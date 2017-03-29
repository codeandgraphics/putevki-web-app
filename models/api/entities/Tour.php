<?php

namespace Models\Api\Entities;

class Tour
{
	public $id;
	public $name;
	public $room;
	public $placement;
	public $adults;
	public $child;

	public $meal;

	public $price;
	public $fuel;
	public $visa;

	public $date;
	public $nights;

	public $operator;

	public function __construct($tour = null)
	{
		if($tour) {
			$this->id = $tour->tourid;
			$this->name = $tour->tourname;
			$this->room = $tour->room;
			$this->placement = $tour->placement;
			$this->adults = $tour->adults;
			$this->child = $tour->child;

			$this->meal = new \stdClass();
			$this->meal->type = $tour->meal;
			$this->meal->code = $tour->mealcode;
			$this->meal->russian = $tour->mealrussian;

			$this->price = $tour->price;
			$this->fuel = $tour->fuel;
			$this->visa = $tour->visa;

			$this->date = $tour->flydate;
			$this->nights = $tour->nights;

			$this->operator = new \stdClass();
			$this->operator->id = $tour->operatorcode;
			$this->operator->name = $tour->operatorname;
		}
	}
}