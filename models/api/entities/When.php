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

    public function fromStored($when = null) {
        if($when) {
            $this->dateFrom = $when->dateFrom ? : $this->dateFrom;
            $this->dateTo = $when->dateTo ? : $this->dateTo;
            $this->nightsFrom = $when->nightsFrom ? : $this->nightsFrom;
            $this->nightsTo = $when->nightsTo ? : $this->nightsTo;
        }
    }

	public function isDateRange() {
	    return $this->dateFrom !== $this->dateTo;
    }

    public function isNightsRange() {
	    return $this->nightsFrom !== $this->nightsTo;
    }
}