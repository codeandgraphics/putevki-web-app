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
    public $nameRod;
    public $nameDat;
    public $nameVin;
    public $nameTvo;
    public $namePre;
    public $uri;
    public $lat;
    public $lon;
    public $zoom;
    public $flightCity = 0;
    public $phone = 0;
    public $main = 0;
    public $active = 1;
    public $popularCountries;
    public $metaDescription;
    public $metaKeywords;
    public $metaText;

    public function initialize()
    {
        $this->belongsTo('flightCity', Departures::name(), 'id', [
            'alias' => 'departure'
        ]);
    }

    public function afterFetch()
    {
        $this->popularCountries = explode(',', $this->popularCountries);
    }

    public function beforeSave()
    {
        $this->popularCountries = implode(',', $this->popularCountries);
    }

    public function beforeValidation()
    {
        $cases = Morpher::cases($this->name);

        $this->nameRod = $cases->nameRod;
        $this->nameDat = $cases->nameDat;
        $this->nameVin = $cases->nameVin;
        $this->nameTvo = $cases->nameTvo;
        $this->namePre = $cases->namePre;

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
                    $messages[] =
                        'Заполнение поля ' .
                        $message->getField() .
                        ' обязательно';
                    break;
            }
        }

        return $messages;
    }

    /**
     * @param $uri
     * @return Cities|Model
     */
    public static function findFirstByUri($uri)
    {
        return self::findFirst("uri = '$uri'");
    }
}
