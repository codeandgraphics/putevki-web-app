<?php

namespace Models\Tourvisor;

use Interfaces\ITourvisorEntity;
use Models\BaseModel;
use Phalcon\Mvc\Model;

class Operators extends BaseModel implements ITourvisorEntity
{
    public $id;
    public $name;
    public $fullName;
    public $russian;
    public $onlineBooking;
    public $legal;
    public $guarantee;
    public $about;

    public function initialize()
    {
        $this->setSource('tourvisor_operators');
    }

    public function fromTourvisor($item)
    {
        $this->id = $item->id;
        $this->name = $item->name;
        $this->fullName = $item->fullname;
        $this->russian = $item->russian;
        $this->onlineBooking = $item->onlinebooking;
    }

    public function format()
    {
        $operator = new \stdClass();

        $operator->id = $this->id;
        $operator->name = $this->name;
        $operator->fullName = $this->fullName;
        $operator->russian = $this->russian;
        $operator->onlineBooking = $this->onlineBooking;
        $operator->legal = $this->legal;
        $operator->guarantee = $this->guarantee;
        $operator->about = $this->about;

        return $operator;
    }

    /**
     * @param null $parameters
     * @return Operators|Model
     */
    public static function findFirst($parameters = null)
    {
        return parent::findFirst($parameters);
    }

    /**
     * @param mixed $parameters
     * @return Operators[]|Model\ResultsetInterface
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }
}
