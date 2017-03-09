<?php
namespace Models\Api\Entities;

class When {

	public $dateFrom;
	public $dateTo;
	public $nightsFrom;
	public $nightsTo;


	public function __construct($when = null)
	{
		if($when) {
			$this->dateFrom = $when->dateFrom;
			$this->dateTo = $when->dateTo;
			$this->nightsFrom = $when->nightsFrom;
			$this->nightsTo = $when->nightsTo;
		}
	}
}