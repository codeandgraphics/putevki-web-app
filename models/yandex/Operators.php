<?php

namespace Models\Yandex;

use Phalcon\Mvc\Model;

class Operators extends Model
{
    public $id;
    public $name;

    public function getSource()
    {
        return 'yandex_operators';
    }

    public function initialize()
    {
        $this->hasOne('id', 'Models\References\Operators', 'ya_ref_id', [
            'alias' => 'reference'
        ]);
    }
}