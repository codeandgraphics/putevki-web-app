<?php

namespace Models\Yandex;

use Phalcon\Mvc\Model;

class Countries extends Model
{
    public $id;
    public $name;
    public $name_en;

    public function getSource()
    {
        return 'yandex_countries';
    }

    public function initialize()
    {
        $this->hasOne('id', 'Models\References\Countries', 'ya_ref_id', [
            'alias' => 'reference'
        ]);
    }
}