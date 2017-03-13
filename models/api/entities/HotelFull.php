<?php

namespace Models\Api\Entities;

class HotelFull
{
	public $id;
	public $name;
	public $description;
	public $stars;
	public $rating;
	public $images = [];

	public $params;

	public $about;

	public $country;
	public $region;

	public function __construct($hotel = null)
	{
		if($hotel) {
			$this->name = $hotel->name;
			$this->description = $hotel->description;
			$this->stars = (int) $hotel->stars;
			$this->rating = $hotel->rating;

			if(property_exists($hotel, 'images') && property_exists($hotel->images, 'image')) {
				$this->images = $hotel->images->image;
			}

			$this->params = new \stdClass();
			$this->params->build = $hotel->build;
			$this->params->repair = $hotel->repair;
			$this->params->placement = $hotel->placement;
			$this->params->square = $hotel->square;
			$this->params->phone = $hotel->phone;
			$this->params->site = $hotel->site;
			$this->params->lat = $hotel->coord1;
			$this->params->lon = $hotel->coord2;

			$this->about = new \stdClass();
			$this->about->territory = $hotel->territory;
			$this->about->inroom = $hotel->inroom;
			$this->about->roomtypes = $hotel->roomtypes;
			$this->about->services = $hotel->services;
			$this->about->servicefree = $hotel->servicefree;
			$this->about->child = $hotel->child;
			$this->about->beach = $hotel->beach;
			$this->about->meallist = $hotel->meallist;

			$this->country = new \stdClass();
			$this->country->id = $hotel->countrycode;
			$this->country->name = $hotel->country;

			$this->region = new \stdClass();
			$this->region->id = $hotel->regioncode;
			$this->region->name = $hotel->region;
		}
	}
}