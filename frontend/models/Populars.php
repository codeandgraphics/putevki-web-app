<?php

namespace Frontend\Models;

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
		$this->belongsTo('countryId', 'Models\Tourvisor\Countries', 'id', [
            'alias' => 'country'
        ]);
		$this->belongsTo('regionId', 'Models\Tourvisor\Regions', 'id', [
            'alias' => 'region'
        ]);
	}

	public static function loadWeather($ids)
	{
		
		$frontCache = new \Phalcon\Cache\Frontend\Data([
			'lifetime'	=> 3600
		]);
		
		$cache = new \Phalcon\Cache\Backend\File($frontCache, [
			'cacheDir'	=> "../app/cache/"
		]);
		
		$cacheKey = 'weather'.implode('-',$ids).'.cache';
		$data = $cache->get($cacheKey);
		
		
		if($data === null)
		{		
			$data = file_get_contents('http://api.openweathermap.org/data/2.5/group?id='.implode(',',$ids).'&units=metric');
			
			$cache->save($cacheKey, $data);
		}
		
		$weather = json_decode($data);
		
		return $weather;
	}
	
}