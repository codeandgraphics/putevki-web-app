<?php

namespace Models\Api\Entities;

class Hotel
{
	public $id;
	public $name;
	public $description;
	public $stars;
	public $rating;
	public $picture;

	public $price;
	public $country;
	public $region;

	public $tours = [];

	public function __construct($hotel = null)
	{
		if($hotel) {
			$this->id = $hotel->hotelcode;
			$this->name = $hotel->hotelname;
			$this->description = $hotel->hoteldescription;
			$this->stars = $hotel->hotelstars;
			$this->rating = $hotel->hotelrating;
			$this->picture = $hotel->picturelink;

			$this->price = $hotel->price;

			$this->country = new \stdClass();
			$this->country->id = $hotel->countrycode;
			$this->country->name = $hotel->countryname;

			$this->region = new \stdClass();
			$this->region->id = $hotel->regioncode;
			$this->region->name = $hotel->regionname;

			foreach($hotel->tours->tour as $tour) {
				$this->tours[] = new Tour($tour);
			}

			$this->tours = json_encode($this->tours);
		}
	}
}