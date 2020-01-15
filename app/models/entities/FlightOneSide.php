<?php

namespace Models\Entities;

class FlightOneSide
{
    public $company;
    public $number;
    public $plane;

    public $departure;
    public $arrival;

    public $onDemand = false;

    public function __construct($oneSide = null)
    {
        if ($oneSide) {
            $this->company = new \stdClass();
            $this->company->id = $oneSide->company->id;
            $this->company->name = $oneSide->company->name;
            $this->company->logo = $oneSide->company->thumb;

            $this->number = $oneSide->number;
            $this->plane = $oneSide->plane;

            $this->departure = new \stdClass();
            $this->departure->time = $oneSide->departure->time;
            $this->departure->port = new \stdClass();
            $this->departure->port->id = $oneSide->departure->port->id;
            $this->departure->port->name = $oneSide->departure->port->name;

            $this->arrival = new \stdClass();
            $this->arrival->time = $oneSide->arrival->time;
            $this->arrival->port = new \stdClass();
            $this->arrival->port->id = $oneSide->arrival->port->id;
            $this->arrival->port->name = $oneSide->arrival->port->name;

            $this->onDemand = $oneSide->onDemand;
        }
    }
}
