<?php

namespace Models\Entities;

use Models\Branches;

class Office
{
	public $id;
	public $name;
	public $address;
	public $timetable;
	public $lat;
	public $lon;

	public function __construct(Branches $branch)
	{
		if ($branch) {
			$this->id = (int)$branch->id;
			$this->name = $branch->name;
			$this->address = $branch->address;
			$this->timetable = $branch->timetable;
			$this->lat = $branch->lat;
			$this->lon = $branch->lon;
		}
	}
}