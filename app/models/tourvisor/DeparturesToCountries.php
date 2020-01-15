<?php

namespace Models\Tourvisor;

use Phalcon\Di;
use Models\BaseModel;
use Phalcon\Mvc\Model;

class DeparturesToCountries extends BaseModel
{
    public $departureId;
    public $countryId;

    public function initialize()
    {
        $this->setSource('tourvisor_departures_to_countries');
    }

    /**
     * @param mixed $parameters
     * @return DeparturesToCountries|Model
     */
    public static function findFirst($parameters = null)
    {
        return parent::findFirst($parameters);
    }

    /**
     * @param mixed $parameters
     * @return DeparturesToCountries[]|Model\ResultsetInterface
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }
}
