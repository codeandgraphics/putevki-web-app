<?php

namespace Models\Tourvisor;

use Interfaces\ITourvisorEntity;
use Models\BaseModel;
use Phalcon\Mvc\Model;

class Meals extends BaseModel implements ITourvisorEntity
{
    public $id;
    public $name;
    public $fullName;
    public $russian;
    public $russianFull;

    public function initialize()
    {
        $this->setSource('tourvisor_meals');
    }

    public function fromTourvisor($meal)
    {
        $this->id = $meal->id;
        $this->name = $meal->name;
        $this->fullName = $meal->fullname;
        $this->russian = $meal->russian;
        $this->russianFull = $meal->russianfull;
    }

    public function format()
    {
        $meal = new \stdClass();

        $meal->id = $this->id;
        $meal->name = $this->name;
        $meal->fullName = $this->fullName;
        $meal->russian = $this->russian;
        $meal->russianFull = $this->russianFull;

        return $meal;
    }

    /**
     * @param null $parameters
     * @return Meals|Model
     */
    public static function findFirst($parameters = null)
    {
        return parent::findFirst($parameters);
    }

    /**
     * @param mixed $parameters
     * @return Meals[]|Model\ResultsetInterface
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }
}
