<?php

namespace Models\Entities;

class SearchHotel {
	public $id;
	public $name;
	public $country;
	public $countryName;
	public $region;
	public $regionName;

	public function __construct($hotel = null) {
		if($hotel) {
			$this->id = (int) $hotel->id;
			$this->name = $hotel->name;
			$this->country = (int) $hotel->country;
			$this->countryName = $hotel->countryName;
			$this->region = (int) $hotel->region;
			$this->regionName = $hotel->regionName;
		}
	}
}