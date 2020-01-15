<?php

namespace Models\Entities;

use Models\Tourvisor\Departures;

class Departure
{
    public $id;
    public $name;
    public $nameFrom;

    public function __construct(Departures $departure = null)
    {
        if ($departure) {
            $this->id = (int) $departure->id;
            $this->name = $departure->name;
            $this->nameFrom = $departure->nameFrom;
        }
    }
}
