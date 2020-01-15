<?php

namespace Models\Tourvisor;

use Interfaces\ITourvisorEntity;
use Models\BaseModel;
use Phalcon\Mvc\Model;

class Countries extends BaseModel implements ITourvisorEntity
{
    public $id;
    public $name;
    public $popular;

    public function initialize()
    {
        $this->setSource('tourvisor_countries');

        $this->hasMany('id', Regions::name(), 'countryId', [
            'alias' => 'regions'
        ]);
    }

    public function fromTourvisor($item)
    {
        $this->id = $item->id;
        $this->name = $item->name;
    }

    public function format()
    {
        $country = new \stdClass();

        $country->id = $this->id;
        $country->name = $this->name;

        return $country;
    }

    /**
     * @param null $parameters
     * @return Countries|Model
     */
    public static function findFirst($parameters = null)
    {
        return parent::findFirst($parameters);
    }

    /**
     * @param mixed $parameters
     * @return Countries[]|Model\ResultsetInterface
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }
}
