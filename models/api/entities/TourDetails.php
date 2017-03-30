<?php

namespace Models\Api\Entities;

class TourDetails
{
	public $flights = [];
	public $info;
	public $actualized = false;

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

			$this->actualized = true;

			if($details->iserror && count($this->flights) === 0) {
				$this->actualized = false;
				unset($this->flights);
				unset($this->info);
			}
		}
	}
}