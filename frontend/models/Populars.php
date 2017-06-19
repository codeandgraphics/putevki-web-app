<?php

namespace Frontend\Models;

use Models\Tourvisor\Regions;
use Phalcon\Mvc\Model;

class Populars extends Model
{	
	public $id;
	public $countryId = null;
	public $regionId = null;
	public $date = null;
	public $nights = 7; //TODO
	public $people = null;
	public $active = 1;
	
	public $weather = null;
	
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