<?php

namespace Models\Entities;

use Models\Tourvisor\Hotels;

class HotelTypes {
    const KEYS_SEPARATOR = ';';

    public $deluxe;
    public $beach;
    public $family;
    public $active;
    public $relax;
    public $health;
    public $city;

    public static function fromHotel(Hotels $hotel) {
        $types = '';
        $types .= $hotel->deluxe;
        $types .= $hotel->beach;
        $types .= $hotel->family;
        $types .= $hotel->active;
        $types .= $hotel->relax;
        $types .= $hotel->health;
        $types .= $hotel->city;
        return $types;
    }

    public static function getMask() {
        $self = new HotelTypes();
        $keys = array_keys(get_object_vars($self));
        return implode(self::KEYS_SEPARATOR, $keys);
    }
}