<?php

namespace Interfaces;

interface ITourvisorEntity
{
    public function fromTourvisor($item);

    public function format();

    public static function findFirst($parameters = null);

    public static function find($parameters = null);
}
