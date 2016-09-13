<?php

namespace Models\Yandex;

use Phalcon\Mvc\Model;

class Hotels extends Model
{
	public $id;
	public $name;
	public $name_en;
	public $stars;
	public $region_id;

	public function getSource()
	{
		return 'yandex_hotels';
	}

	public function initialize()
	{
		$this->hasOne('id', 'Models\References\Hotels', 'ya_ref_id', [
			'alias' => 'reference'
		]);

		$this->hasOne('region_id', 'Models\Yandex\Regions', 'id', [
			'alias' => 'region'
		]);
	}
}