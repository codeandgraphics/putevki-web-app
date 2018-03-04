<?php

namespace Models;

use Backend\Models\Users;

class Branches extends BaseModel
{
	const DELAY_TIME = 600;

	public $id;
	public $name;
	public $email;
	public $additionalEmails;
	public $address;
	public $addressDetails;
	public $timetable;
	public $phone = 0;
	public $site;
	public $lat;
	public $lon;
	public $cityId = 0;
	public $main = 0;
	public $managerId;
	public $active;

	public $managerPassword;

	public $metaDescription;
	public $metaKeywords;
	public $metaText;

	public function initialize()
	{
		$this->belongsTo('cityId', Cities::name(), 'id', [
			'alias' => 'city'
		]);

		$this->hasOne('managerId', Users::name(), 'id', [
			'alias'	=> 'manager'
		]);
	}

	public static function findPublic($parameters) {
		$parameters['columns'] = 'id, name, phone, lat, lon, address, addressDetails, timetable, main, active';
		return self::find($parameters);
	}

}