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
use Phalcon\Mvc\Dispatcher;

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
        $this->where->fromStored((object) $object->where);
        $this->when->fromStored((object) $object->when);
        $this->people->fromStored((object) $object->people);
        $this->filters->fromStored((object) $object->filters);
    }

    public function fromSearchForm($object) {
        $this->from = $object->from ? : $this->from;
        $this->where->fromStored((object) $object->where);
        $this->when->fromStored((object) $object->when);
        $this->people->fromStored((object) $object->people);
        $this->filters->fromStored((object) $object->filters);
    }

    /**
     * @param $dispatcher Dispatcher
     */
    public function fromDispatcher($dispatcher) {
        $from = $dispatcher->getParam('from');
        $fromEntity = Departures::findFirst("name='$from'");
        $this->from = $fromEntity ? (int) $fromEntity->id : $this->from;

        $this->whereFromQuery($dispatcher->getParam('where'), $dispatcher->getParam('hotelId'));
        $this->whenFromQuery($dispatcher->getParam('date'), $dispatcher->getParam('nights'));
        $this->peopleFromQuery($dispatcher->getParam('adults'), $dispatcher->getParam('children'));
        $this->filtersFromQuery($dispatcher->getParam('stars'), $dispatcher->getParam('meal'));
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
        $queryString .= $this->when->isDateRange() ? $this->when->notRangeDate() : $this->when->dateFrom;

        $queryString .= $this->when->isNightsRange() ? '/~' : '/';
        $queryString .= $this->when->isNightsRange() ? $this->when->notRangeNights() : $this->when->nightsFrom;

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

    private function whereFromQuery($where, $hotelId) {
        preg_match_all('/\((.*?)\)/', $where, $matches);

        $regionName = false;

        if($matches[1])
        {
            $regionName = $matches[1][0] ?: false;
        }

        if($regionName)
        {
            $region = Regions::findFirst("name='$regionName'");
            if($region)
            {
                $this->where->country = (int) $region->countryId;
                $this->where->regions = [(int) $region->id];
            }
        }
        else
        {
            $countryName = $where;
            $country = Countries::findFirst("name='$countryName'");
            if($country)
            {
                $this->where->country = (int) $country->id;
                $this->where->regions = [];
            }
        }

        $this->where->hotels = $hotelId ? : null;
    }

    private function whenFromQuery($date, $nights) {
        if(strpos($date,'~') === 0)
        {
            $date = \DateTime::createFromFormat('d.m.Y', str_replace('~', '', $date));
            $this->when->dateFrom = $date->sub(new \DateInterval('P' . When::DATE_RANGE . 'D'))->format('d.m.Y');
            $this->when->dateTo = $date->add(new \DateInterval('P' . (When::DATE_RANGE * 2) . 'D'))->format('d.m.Y');
        } else {
            $this->when->dateFrom = $date;
            $this->when->dateTo = $date;
        }

        if(strpos($nights,'~') === 0)
        {
            $nights = str_replace('~', '', $nights);
            $this->when->nightsFrom = $nights - When::NIGHTS_RANGE;
            $this->when->nightsTo = $nights + When::NIGHTS_RANGE;
        } else {
            $this->when->nightsFrom = (int) $nights;
            $this->when->nightsTo = (int) $nights;
        }
    }

    private function peopleFromQuery($adults, $children) {
        if($adults) {
            $this->people->adults = $adults;
        }

        if($children) {
            $this->people->children = explode(People::CHILDREN_SEPARATOR, $children);
        }
    }

    private function filtersFromQuery($stars, $meal) {
        if($stars) {
            $this->filters->stars = $stars;
        }
        if($meal) {
            $this->filters->meal = $meal;
        }
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