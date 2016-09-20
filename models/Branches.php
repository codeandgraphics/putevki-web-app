<?php

namespace Models;

use Phalcon\Mvc\Model;

class Branches extends Model
{
	const DELAY_TIME = 600;

	public $id;
	public $name = null;
	public $email;
	public $additionalEmails;
	public $address = null;
	public $timetable = null;
	public $phone = 0;
	public $site = null;
	public $lat = null;
	public $lon = null;
	public $cityId = 0;
	public $main = 0;
	public $manager_id;
	public $active;

	public $managerPassword;

	public $meta_description = null;
	public $meta_keywords = null;
	public $meta_text = null;

	public function initialize()
	{
		$this->belongsTo('cityId', 'Models\Cities', 'id', array(
			'alias' => 'city'
		));

		$this->hasOne('manager_id','\Backend\Models\Users', 'id', [
			'alias'	=> 'manager'
		]);
	}

}