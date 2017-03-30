<?php

namespace Models\Api\Entities;

class Flight
{
	public $forward = [];
	public $backward = [];

	public $forwardDate;
	public $backwardDate;

	public $isDefault = false;


	public function __construct($flight = null)
	{
		if($flight) {
			foreach ($flight->forward as $item) {
				$this->forward[] = new FlightOneSide($item);
			}

			foreach ($flight->backward as $item) {
				$this->backward[] = new FlightOneSide($item);
			}

			$this->forwardDate = $flight->dateforward;
			$this->backwardDate = $flight->datebackward;

			$this->price = $flight->price->value;

			$this->fuel = new \stdClass();
			$this->fuel->purpose = $flight->fuelcharge->purpose;
			$this->fuel->value = $flight->fuelcharge->value;
			$this->fuel->perPerson = $flight->fuelcharge->perPerson;

			$this->default = $flight->isdefault;
		}
	}
}