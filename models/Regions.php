<?php

namespace Models;

use Phalcon\Mvc\Model;
use Models\Tourvisor;

class Regions extends BaseModel
{

	public $tourvisorId;
	public $uri;
	public $title;
	public $about;
	public $popular;
	public $active;

	public function initialize()
	{
		$this->belongsTo('tourvisorId', Tourvisor\Regions::name(), 'id', [
			'alias' => 'tourvisor'
		]);
	}

	/**
	 * @param $uri
	 * @return Regions|Model
	 */
	public static function findFirstByUri($uri) {
		return self::findFirst("uri = '$uri'");
	}

	/**
	 * @param $id
	 * @return Regions|Model
	 */
	public static function findFirstByTourvisorId($id) {
		$region = self::findFirst("tourvisorId = '$id'");
		if(!$region) {
			$region = new Regions();
			$region->tourvisorId = $id;
			$region->save();
		}
		return $region;
	}
}