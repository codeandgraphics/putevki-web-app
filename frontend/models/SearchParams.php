<?php

namespace Frontend\Models;

use Models\Api\Entities\Filters;
use Models\Api\Entities\People;
use Models\Api\Entities\When;
use Models\Api\Entities\Where;
use Models\Tourvisor\Countries;
use Models\Tourvisor\Departures;
use Models\Tourvisor\Hotels;
use Models\Tourvisor\Regions;
use Phalcon\Di;

class SearchParams {
    public $from;

    /**
     * @var Where
     */
    public $where;

    /**
     * @var When
     */
    public $when;

    /**
     * @var People
     */
    public $people;

    /**
     * @var Filters
     */
    public $filters;

    private $config;

    public function __construct() {
        $this->config = Di::getDefault()->get('config');

        $this->from = (int) $this->config->defaults->from;
        $this->where = $this->defaultWhere();
        $this->when = $this->defaultWhen();
        $this->people = $this->defaultPeople() ;
        $this->filters = $this->defaultFilters();
    }

    public function fromStored($object) {
        $this->from = $object->from ? : (int) $this->config->defaults->from;
        $this->where->fromStored($object->where);
        $this->when->fromStored($object->when);
        $this->people->fromStored($object->people);
        $this->filters->fromStored($object->filters);
    }

    public function fromSearchForm($object) {
        $this->from = $object->from ? : $this->from;
        $this->where->fromStored($object->where);
        $this->when->fromStored($object->when);
        $this->people->fromStored($object->people);
        $this->filters->fromStored($object->filters);
    }

    public function buildQueryString()
    {
        $queryString = $this->fromEntity()->name;

        $queryString .= '/' . $this->countryEntity()->name;

        if($this->where->regions[0])
        {
            $queryString .= '(' . $this->regionEntity()->name . ')';
        }

        if($this->where->hotels)
        {
            $hotelName = str_replace(array(' ', '&'), array('_', 'AND'), $this->hotelEntity()->name);
            $queryString .= '/' . $hotelName . '-' . $this->where->hotels;
        }

        $queryString .= $this->when->isDateRange() ? '/~' : '/';
        $queryString .= implode('.', array_reverse(explode('.',$this->when->dateTo))); //Хз что быстрее, strtotime или это

        $queryString .= $this->when->isNightsRange() ? '/~' : '/';
        $queryString .= $this->when->nightsFrom;

        $queryString .= '/' . $this->people->adults;
        $queryString .= '/' . $this->people->getChildrenString();

        $queryString .= '/' . $this->filters->stars;
        $queryString .= '/' . $this->filters->meal;

        return $queryString;
    }

    public function isHotelQuery() {
        return (bool) $this->where->hotels;
    }


    public function fromEntity() {
        return Departures::findFirst("id='$this->from'");
    }

    public function countryEntity() {
        return Countries::findFirst("id='" . $this->where->country . "'");
    }

    public function regionEntity() {
        return Regions::findFirst("id='" . $this->where->regions[0] . "'");
    }

    public function hotelEntity() {
        return Hotels::findFirst("id='" . $this->where->hotels . "'");
    }

    private function defaultWhere() {
        $where = new Where();
        $where->country = (int) $this->config->defaults->country;
        return $where;
    }

    private function defaultWhen() {
        $when = new When();
        $date = new \DateTime();
        $date->add(new \DateInterval('P1D'));
        $when->dateFrom = $date->format('d.m.Y');
        $date->add(new \DateInterval('P7D'));
        $when->dateTo = $date->format('d.m.Y');

        $when->nightsFrom = $this->config->defaults->nights;
        $when->nightsTo = $this->config->defaults->nights;
        return $when;
    }

    private function defaultPeople() {
        $people = new People();
        $people->adults = (int) $this->config->defaults->adults;
        return $people;
    }

    private function defaultFilters() {
        return new Filters();
    }
}