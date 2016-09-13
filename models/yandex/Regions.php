<?php

namespace Models\Yandex;

use Phalcon\Mvc\Model;

class Regions extends Model
{
	public $id;
	public $name;
	public $name_en;
	public $country_id;

	public function getSource()
	{
		return 'yandex_regions';
	}
}