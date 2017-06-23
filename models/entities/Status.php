<?php

namespace Models\Entities;

class Status
{

	public $state;
	public $hotels;
	public $tours;
	public $price;
	public $progress;
	public $time;


	public function __construct($status = null)
	{
		if ($status) {
			$this->state = $status->state;
			$this->hotels = $status->hotelsfound;
			$this->tours = $status->toursfound;
			$this->price = new \stdClass();
			$this->price->min = $status->minprice;
			$this->price->max = $status->maxprice;
			$this->progress = $status->progress;
			$this->time = $status->timepassed;
		}
	}
}