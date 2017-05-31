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

	public $reviews = [];

	public $country;
	public $region;

	public function __construct($hotel = null)
	{
		if($hotel) {
			$this->name = $hotel->name;
			$this->description = Hotel::removeEntities($hotel->description);
			$this->stars = (int) $hotel->stars;
			$this->rating = $hotel->rating;

			if(property_exists($hotel, 'images') && property_exists($hotel->images, 'image')) {
				$this->images = $hotel->images->image;
			}

			$this->params = new \stdClass();
			$this->params->build = $hotel->build;
			$this->params->repair = $hotel->repair;
			$this->params->placement = Hotel::removeEntities($hotel->placement);
			$this->params->square = $hotel->square;
			$this->params->phone = $hotel->phone;
			$this->params->site = $hotel->site;
			$this->params->lat = $hotel->coord1;
			$this->params->lon = $hotel->coord2;

			$this->about = new \stdClass();
			$this->about->territory = Hotel::removeEntities($hotel->territory);
			$this->about->inroom = Hotel::removeEntities($hotel->inroom);
			$this->about->roomtypes = Hotel::removeEntities($hotel->roomtypes);
			$this->about->services = Hotel::removeEntities($hotel->services);
			$this->about->servicefree = Hotel::removeEntities($hotel->servicefree);
			$this->about->child = Hotel::removeEntities($hotel->child);
			$this->about->beach = Hotel::removeEntities($hotel->beach);
			$this->about->meallist = Hotel::removeEntities($hotel->meallist);

			if(property_exists($hotel, 'reviews') && property_exists($hotel->reviews, 'review')) {
				foreach($hotel->reviews->review as $review) {
					$this->reviews[] = new Review($review);
				}
			}

			$this->country = new \stdClass();
			$this->country->id = $hotel->countrycode;
			$this->country->name = $hotel->country;

			$this->region = new \stdClass();
			$this->region->id = $hotel->regioncode;
			$this->region->name = $hotel->region;
		}
	}
}