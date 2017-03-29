<?php

namespace Models\Api\Entities;

class Flight
{
	public $forward = [];
	public $backward = [];


	public function __construct($flight = null)
	{
		if($flight) {
			foreach ($flight->forward as $item) {
				$this->forward[] = new FlightOneSide($item);
			}

			foreach ($flight->backward as $item) {
				$this->backward[] = new FlightOneSide($item);
			}
		}
	}
}