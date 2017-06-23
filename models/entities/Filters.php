<?php

namespace Models\Entities;

class Filters
{

	public $stars = 3;
	public $meal = 3;
	public $rating = 3;
	public $operator = 0;

	public function __construct($filters = null)
	{
		if ($filters) {
			$this->stars = (int)$filters->stars;
			$this->meal = (int)$filters->meal;
			$this->rating = (int)$filters->rating;
			$this->operator = (int)$filters->operator;
		}
	}

	public function fromStored($filters = null)
	{
		if ($filters) {
			$this->stars = $filters->stars ?: $this->stars;
			$this->meal = $filters->meal ?: $this->meal;
			$this->rating = $filters->rating ?: $this->rating;
			$this->operator = $filters->operator ?: $this->operator;
		}
	}
}