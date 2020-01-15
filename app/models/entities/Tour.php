<?php

namespace Models\Entities;

class Tour
{
    public $id;
    public $name;
    public $room;
    public $placement;
    public $adults;
    public $child;

    public $meal;

    public $price;
    public $fuel;
    public $visa;

    public $date;
    public $nights;

    public $operator;

    public function __construct($tour = null)
    {
        if ($tour) {
            $this->id = (int) $tour->tourid;
            $this->name = $tour->tourname;
            $this->room = $tour->room;
            $this->placement = $tour->placement;
            $this->adults = (int) $tour->adults;
            $this->child = (int) $tour->child;

            $this->meal = new \stdClass();
            $this->meal->type = $tour->meal;
            $this->meal->code = (int) $tour->mealcode;
            $this->meal->russian = $tour->mealrussian;

            $this->price = (int) $tour->price;
            $this->fuel = (int) $tour->fuelcharge;
            $this->visa = (int) $tour->visa;

            $this->date = $tour->flydate;
            $this->nights = (int) $tour->nights;

            $this->operator = new \stdClass();
            $this->operator->id = (int) $tour->operatorcode;
            $this->operator->name = str_replace(
                'TezTour',
                'TEZ TOUR',
                $tour->operatorname
            );
        }
    }
}
