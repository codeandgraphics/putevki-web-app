<?php

namespace Models\Api\Entities;

class TourDetails
{
	public $flights = [];
	public $info;

	public function __construct($details = null)
	{
		if($details) {

			foreach($details->flights as $item) {
				$this->flights[] = new Flight($item);
			}

			$this->info = new \stdClass();
			$this->info->flags = new \stdClass();
			$this->info->flags->meal = !$details->flags->nomeal;
			$this->info->flags->insurance = !$details->flags->nomedinsurance;
			$this->info->flags->flight = !$details->flags->noflight;
			$this->info->flags->transfer = !$details->flags->notransfer;
		}
	}
}