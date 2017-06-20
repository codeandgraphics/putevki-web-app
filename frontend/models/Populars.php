<?php

namespace Frontend\Models;

use Models\Tourvisor\Regions;
use Phalcon\Mvc\Model;

class Populars extends Model
{	
	public $id;
	public $countryId;
	public $regionId;
	public $date;
	public $nights = 7; //TODO
	public $people;
	public $active = 1;
	
	public $weather;
	
	public function initialize()
	{
		$this->belongsTo('countryId', Regions::name(), 'id', [
            'alias' => 'country'
        ]);
		$this->belongsTo('regionId', Regions::name(), 'id', [
            'alias' => 'region'
        ]);
	}
}