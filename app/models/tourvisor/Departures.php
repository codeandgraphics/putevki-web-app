<?php

namespace Models\Tourvisor;

use Interfaces\ITourvisorEntity;
use Models\BaseModel;
use Phalcon\Mvc\Model;

class Departures extends BaseModel implements ITourvisorEntity
{
    public $id;
    public $name;
    public $nameFrom;

    public function initialize()
    {
        $this->setSource('tourvisor_departures');
    }

    public function fromTourvisor($item)
    {
        $this->id = $item->id;
        $this->name = $item->name;
        $this->nameFrom = $item->namefrom;
    }

    public function format()
    {
        $departure = new \stdClass();

        $departure->id = $this->id;
        $departure->name = $this->name;
        $departure->nameFrom = $this->nameFrom;

        return $departure;
    }

    /**
     * @param mixed $parameters
     * @return Departures|Model
     */
    public static function findFirst($parameters = null)
    {
        return parent::findFirst($parameters);
    }

    /**
     * @param mixed $parameters
     * @return Departures[]|Model\ResultsetInterface
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }
}
