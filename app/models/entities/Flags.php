<?php

namespace Models\Entities;

class Flags
{
    public $meal = false;
    public $insurance = false;
    public $flight = false;
    public $transfer = false;

    public function __construct($flags = null)
    {
        if ($flags) {
            $this->meal = !$flags->nomeal;
            $this->insurance = !$flags->nomedinsurance;
            $this->flight = !$flags->noflight;
            $this->transfer = !$flags->notransfer;
        }
    }
}
