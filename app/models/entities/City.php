<?php

namespace Models\Entities;

use Models\Cities;

class City
{
    public $id;
    public $name;
    public $lat;
    public $lon;
    public $offices = [];

    public function __construct(Cities $city)
    {
        if ($city) {
            $this->id = (int) $city->id;
            $this->name = $city->name;
            $this->lat = $city->lat;
            $this->lon = $city->lon;
        }
    }
}
