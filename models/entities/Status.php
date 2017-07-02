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
			$this->hotels = (int) $status->hotelsfound;
			$this->tours = (int) $status->toursfound;
			$this->price = new \stdClass();
			$this->price->min = (int) $status->minprice;
			$this->price->max = (int) $status->maxprice;
			$this->progress = (int) $status->progress;
			$this->time = (int) $status->timepassed;
		}
	}
}