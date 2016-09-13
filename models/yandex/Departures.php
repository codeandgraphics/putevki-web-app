<?php

namespace Models\Yandex;

use Phalcon\Mvc\Model;

class Departures extends Model
{
    public $id;
    public $name;
    public $name_en;

    public function getSource()
    {
        return 'yandex_departures';
    }

    public function initialize()
    {
        $this->hasOne('id', 'Models\References\Departures', 'ya_ref_id', [
            'alias' => 'reference'
        ]);
    }
}