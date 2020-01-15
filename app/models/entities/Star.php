<?php

namespace Models\Entities;

use Models\Tourvisor\Stars;

class Star
{
    public $id;
    public $name;

    public function __construct(Stars $star = null)
    {
        if ($star) {
            $this->id = (int) $star->id;
            $this->name = $star->name;
        }
    }
}
