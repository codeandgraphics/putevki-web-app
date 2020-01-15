<?php

namespace Models\Entities;

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

    public $link;

    public $tours = [];

    public function __construct($hotel = null)
    {
        if ($hotel) {
            $this->id = (int) $hotel->hotelcode;
            $this->name = $hotel->hotelname;
            $this->description = self::removeEntities($hotel->hoteldescription);
            $this->stars = (int) $hotel->hotelstars;
            $this->rating = $hotel->hotelrating;
            $this->picture = $hotel->picturelink;

            $this->price = (int) $hotel->price;

            $this->country = new \stdClass();
            $this->country->id = (int) $hotel->countrycode;
            $this->country->name = $hotel->countryname;

            $this->region = new \stdClass();
            $this->region->id = (int) $hotel->regioncode;
            $this->region->name = $hotel->regionname;

            $urlName = str_ireplace(
                [' ', '&'],
                ['_', 'And'],
                ucwords(strtolower($this->name))
            );
            $this->link = '/hotel/' . $urlName . '-' . $this->id;

            foreach ($hotel->tours->tour as $tour) {
                $this->tours[] = new Tour($tour);
            }

            $this->tours = json_encode($this->tours);
        }
    }

    public static function removeEntities($text)
    {
        return trim(strip_tags(html_entity_decode($text)));
    }
}
