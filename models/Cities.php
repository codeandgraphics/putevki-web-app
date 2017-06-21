<?php

namespace Models;

use Phalcon\Di;
use Phalcon\Mvc\Model;
use \Utils\Morpher;

class Cities extends BaseModel
{
	const DELAY_TIME = 600;

	public $id;
	public $name;
	public $name_rod;
	public $name_dat;
	public $name_vin;
	public $name_tvo;
	public $name_pre;
	public $uri;
	public $lat;
	public $lon;
	public $zoom;
	public $flight_city = 0;
	public $phone = 0;
	public $main = 0;
	public $active = 1;
	public $meta_description;
	public $meta_keywords;
	public $meta_text;

	public function initialize()
	{
		$this->belongsTo('flight_city', 'Models\Tourvisor\Departures', 'id', array(
			'alias' => 'departure'
		));
	}

	public function beforeValidation()
	{
		$cases = Morpher::cases($this->name);

		$this->name_rod = $cases->name_rod;
		$this->name_dat = $cases->name_dat;
		$this->name_vin = $cases->name_vin;
		$this->name_tvo = $cases->name_tvo;
		$this->name_pre = $cases->name_pre;

		return true;
	}

	public function getMessages()
	{
		$messages = array();
		foreach (parent::getMessages() as $message) {
			switch ($message->getType()) {
				case 'PresenceOf':
					$messages[] = 'Заполнение поля ' . $message->getField() . ' обязательно';
					break;
			}
		}

		return $messages;
	}

    /**
     * @param $uri
     * @return Cities|Model
     */
    public static function findFirstByUri($uri) {
	    return self::findFirst("uri = '$uri'");
    }

	public static function checkCity($city = null)
	{
		$config = Di::getDefault()->get('config');
		$defaultCity = $config->defaults->city;
		$defaultFlightCity = $config->defaults->flightCity;
		$cookieTimeout = $config->common->cookieTimeout;
		if($city)
		{
			$activeCity = self::findFirst("uri = '$city'");

			if($activeCity)
			{
				setcookie('city', $activeCity->id, time() + $cookieTimeout, '/');
				setcookie('flight_city', $activeCity->flight_city, time() + $cookieTimeout, '/');
				$currentCity = $activeCity;
			}
			else
			{
				if($_COOKIE['city'])
				{
					$currentCity = self::findFirst($_COOKIE['city']);
				}
				else
				{
					setcookie('city', $defaultCity, time() + $cookieTimeout, '/');
					setcookie('flight_city', $defaultFlightCity, time() + $cookieTimeout, '/');
					$currentCity = self::findFirst($defaultCity);
				}
			}
		}
		else
		{
			if(array_key_exists('city', $_COOKIE) && $_COOKIE['city'])
			{
				$currentCity = self::findFirst($_COOKIE['city']);
			}
			else
			{
				setcookie('city', 1, time() + $cookieTimeout, '/');
				setcookie('flight_city', 1, time() + $cookieTimeout, '/');
				$currentCity = self::findFirst(1);
			}
		}

		$currentCity->branches = Branches::find("cityId='".$currentCity->id."'");

		return $currentCity;
	}


}