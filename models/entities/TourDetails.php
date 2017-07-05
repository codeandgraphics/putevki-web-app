<?php

namespace Models\Entities;

class TourDetails
{
	public $flights = [];
	public $info;
	public $addPayments;
	public $contents;
	public $actualized = false;

	public function __construct($details = null)
	{
		if ($details) {
			if(property_exists($details, 'flights')) {
				foreach ($details->flights as $item) {
					$this->flights[] = new Flight($item);
				}
			}

			$this->info = new \stdClass();
			$this->info->flags = new \stdClass();
			$this->info->flags->meal = property_exists($details->flags, 'nomeal') ?
				!$details->flags->nomeal :
				false;

			$this->info->flags->insurance = property_exists($details->flags, 'nomedinsurance') ?
				!$details->flags->nomedinsurance :
				false;

			$this->info->flags->flight = property_exists($details->flags, 'noflight') ?
				!$details->flags->noflight :
				false;

			$this->info->flags->transfer = property_exists($details->flags, 'notransfer') ?
				!$details->flags->notransfer :
				false;

			$this->addPayments = property_exists($details, 'addpayments') ?
				$details->addpayments :
				false;
			$this->contents = property_exists($details, 'contents') ?
				$details->contents :
				false;

			$this->actualized = true;

			if ($details->iserror && count($this->flights) === 0) {
				$this->actualized = false;
				unset($this->flights, $this->info);
			}
		}
	}
}