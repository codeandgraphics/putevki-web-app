<?php

namespace Models;

use Models\Tourvisor\Departures;
use Phalcon\Mvc\Model;
use Utils\Morpher;

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
	public $popular_countries;
	public $meta_description;
	public $meta_keywords;
	public $meta_text;

	public function initialize()
	{
		$this->belongsTo('flight_city', Departures::name(), 'id', [
			'alias' => 'departure'
		]);
	}

	public function afterFetch() {
        $this->popular_countries = explode(',', $this->popular_countries);
    }

	public function beforeSave() {
	    $this->popular_countries = implode(',', $this->popular_countries);
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

	/**
	 * @param mixed|null $filter
	 * @return array
	 */
	public function getMessages($filter = null)
	{
		$messages = [];
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
}