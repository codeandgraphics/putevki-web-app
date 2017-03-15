<?php
namespace Models\Api\Entities;

class Filters {

	public $stars = 3;
	public $meal = 3;
	public $rating = 3;

	public function __construct($filters = null)
	{
		if($filters) {
			$this->stars = (int) $filters->stars;
			$this->meal = (int) $filters->meal;
			$this->rating = (int) $filters->rating;
		}
	}
}