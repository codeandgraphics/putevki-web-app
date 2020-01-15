<?php

namespace Models\Entities;

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
        if ($hotel) {
            $this->name = $hotel->name;
            $this->description = property_exists($hotel, 'description')
                ? Hotel::removeEntities($hotel->description)
                : false;
            $this->stars = (int) $hotel->stars;
            $this->rating = $hotel->rating;

            if (
                property_exists($hotel, 'images') &&
                property_exists($hotel->images, 'image')
            ) {
                $this->images = $hotel->images->image;
            }

            $this->params = new \stdClass();
            $this->params->build = $hotel->build;
            $this->params->repair = $hotel->repair;
            $this->params->placement = Hotel::removeEntities($hotel->placement);
            $this->params->square = $hotel->square;
            $this->params->phone = $hotel->phone;
            $this->params->site = property_exists($hotel, 'site')
                ? $hotel->site
                : false;
            $this->params->lat = $hotel->coord1;
            $this->params->lon = $hotel->coord2;

            $this->about = new \stdClass();
            $this->about->territory = property_exists($hotel, 'territory')
                ? Hotel::removeEntities($hotel->territory)
                : false;
            $this->about->inroom = property_exists($hotel, 'inroom')
                ? Hotel::removeEntities($hotel->inroom)
                : false;
            $this->about->roomtypes = property_exists($hotel, 'roomtypes')
                ? Hotel::removeEntities($hotel->roomtypes)
                : false;
            $this->about->services = property_exists($hotel, 'services')
                ? Hotel::removeEntities($hotel->services)
                : false;
            $this->about->servicefree = property_exists($hotel, 'servicefree')
                ? Hotel::removeEntities($hotel->servicefree)
                : false;
            $this->about->child = property_exists($hotel, 'child')
                ? Hotel::removeEntities($hotel->child)
                : false;
            $this->about->beach = property_exists($hotel, 'beach')
                ? Hotel::removeEntities($hotel->beach)
                : false;
            $this->about->meallist = property_exists($hotel, 'meallist')
                ? Hotel::removeEntities($hotel->meallist)
                : false;

            if (
                property_exists($hotel, 'reviews') &&
                property_exists($hotel->reviews, 'review')
            ) {
                foreach ($hotel->reviews->review as $review) {
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
