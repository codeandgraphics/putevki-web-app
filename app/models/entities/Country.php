<?php

namespace Models\Entities;

use Models\Countries;
use Models\Tourvisor;

class Country
{
    public $id;
    public $name;
    public $popular;
    public $visa;
    public $regions = [];

    public function __construct(
        Countries $country = null,
        Tourvisor\Countries $tourvisorCountry = null
    ) {
        if ($tourvisorCountry) {
            $this->id = (int) $tourvisorCountry->id;
            $this->name = $tourvisorCountry->name;
        }
        if ($country) {
            $this->popular = (int) $country->popular;
            $this->visa = (int) $country->visa;
        }
    }
}
