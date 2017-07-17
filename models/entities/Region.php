<?php

namespace Models\Entities;

use Models\Regions;
use Models\Tourvisor;

class Region
{
	public $id;
	public $name;
	public $popular;

	public function __construct(Regions $region = null, Tourvisor\Regions $tourvisorRegion = null)
	{
		if ($region && $tourvisorRegion) {
			$this->id = (int)$tourvisorRegion->id;
			$this->name = $tourvisorRegion->name;
			$this->popular = (int)$region->popular;
		}
	}
}