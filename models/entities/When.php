<?php

namespace Models\Entities;

use Models\Date;

class When
{

	const DATE_RANGE = 2;
	const NIGHTS_RANGE = 2;

	public $dateFrom;
	public $dateTo;
	public $nightsFrom;
	public $nightsTo;

	public function __construct($when = null)
	{
		if ($when) {
			$this->dateFrom = $when->dateFrom;
			$this->dateTo = $when->dateTo;
			$this->nightsFrom = $when->nightsFrom;
			$this->nightsTo = $when->nightsTo;
		}
	}

	public function fromStored($when = null)
	{
		if ($when) {
			$this->dateFrom = $when->dateFrom ?: $this->dateFrom;
			$this->dateTo = $when->dateTo ?: $this->dateTo;
			$this->nightsFrom = $when->nightsFrom ?: $this->nightsFrom;
			$this->nightsTo = $when->nightsTo ?: $this->nightsTo;
		}
	}

	public function isDateRange()
	{
		return $this->dateFrom !== $this->dateTo;
	}

	public function notRangeDate()
	{
		$date = \DateTime::createFromFormat('d.m.Y', $this->dateFrom);
		return $date->add(new \DateInterval('P' . self::DATE_RANGE . 'D'))->format('d.m.Y');
	}

	public function notRangeNights()
	{
		return $this->nightsFrom + self::NIGHTS_RANGE;
	}

	public function isNightsRange()
	{
		return $this->nightsFrom !== $this->nightsTo;
	}

	public function getDbDateFrom()
	{
		return Date::toDbDate($this->dateFrom);
	}

	public function getDbDateTo()
	{
		return Date::toDbDate($this->dateTo);
	}
}