<?php

namespace Models\References;

use Phalcon\Db;
use Phalcon\Di;
use Phalcon\Mvc\Model;

class Departures extends Model
{
    public $id;
    public $ya_ref_id;

    public function getSource()
    {
        return 'reference_departures';
    }

    public function initialize()
    {
        $this->hasOne('id', 'Models\Tourvisor\Departures', 'id', [
            'alias' => 'tourvisor'
        ]);
        $this->hasOne('ya_ref_id', 'Models\Yandex\Departures', 'id', [
            'alias' => 'yandex'
        ]);
    }

    public static function getReferenced()
    {
        $db = Di::getDefault()->get('db');

        return $db->fetchAll('
			SELECT ref.id, t.name, ref.ya_ref_id, ya.name AS ya_ref_name
			FROM reference_departures AS ref
			INNER JOIN yandex_departures AS ya ON ya.id = ref.ya_ref_id
			INNER JOIN tourvisor_departures AS t ON t.id = ref.id
			ORDER BY name;
		', Db::FETCH_OBJ);
    }
}