<?php

namespace Models\Entities;

use Models\Tourvisor\Regions;

class Region
{
	public $id;
	public $name;
	public $popular;

	public function __construct(Regions $region = null)
	{
		if ($region) {
			$this->id = (int)$region->id;
			$this->name = $region->name;
			$this->popular = (int)$region->popular;
		}
	}
}