<?php
namespace Models\Api\Entities;

use Models\Branches;

class Office {
	public $id;
	public $name;
	public $address;
	public $lat;
	public $lon;

	public function __construct(Branches $branch)
	{
		if($branch) {
			$this->id = (int) $branch->id;
			$this->name = $branch->name;
			$this->address = $branch->address;
			$this->lat = $branch->lat;
			$this->lon = $branch->lon;
		}
	}
}