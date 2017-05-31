<?php

namespace Models\Api\Entities;

class Review {
	public $name;
	public $content;
	public $travel;

	public $positive;
	public $negative;

	public $rate;

	public $time;
	public $date;

	public $link;

	public function __construct($review = null)
	{
		if($review) {
			$this->name = $review->name;
			$this->content = $review->content;
			$this->travel = $review->traveltime;

			$this->positive = $review->positive;
			$this->negative = $review->negative;

			$this->rate = $review->rate;

			$this->time = $review->reviewtime;
			$this->date = $review->reviewdate;

			$this->link = $review->sourcelink;
		}

	}
}