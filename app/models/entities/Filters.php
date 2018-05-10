<?php

namespace Models\Entities;

class Filters
{
    const STARS = 3;
    const MEAL = 3;
    const RATING = 0;
    const OPERATOR = 0;

	public $stars = self::STARS;
	public $meal = self::MEAL;
	public $rating = self::RATING;
	public $operator = self::OPERATOR;

	public function __construct($filters = null)
	{
		if ($filters) {
			$this->stars = (int)$filters->stars;
			$this->meal = (int)$filters->meal;
			$this->rating = (int)$filters->rating;
			$this->operator = property_exists($filters, 'operator') ? (int)$filters->operator : 0;
		}
	}

	public function fromStored($filters = null)
	{
		if ($filters) {
			$this->stars = $filters->stars ? (int) $filters->stars : $this->stars;
			$this->meal = $filters->meal ? (int) $filters->meal : $this->meal;
			$this->rating = $filters->rating ? (int) $filters->rating : $this->rating;
			$this->operator = $filters->operator ? (int) $filters->operator : $this->operator;
		}
	}
}