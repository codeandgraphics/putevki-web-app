<?php

namespace Models;

use Phalcon\Mvc\Model;
use Models\Tourvisor;

class Countries extends BaseModel
{
    public $tourvisorId;
    public $uri;
    public $title;
    public $preview;
    public $about;
    public $metaKeywords;
    public $metaDescription;
    public $popular;
    public $visa;
    public $active;

    public function initialize()
    {
        $this->belongsTo('tourvisorId', Tourvisor\Countries::name(), 'id', [
            'alias' => 'tourvisor'
        ]);
    }

    /**
     * @return Meta
     */
    public function getMeta(): Meta
    {
        return new Meta($this->metaKeywords, $this->metaDescription);
    }

    /**
     * @param $uri
     * @return Countries|Model
     */
    public static function findFirstByUri($uri)
    {
        return self::findFirst("uri = '$uri'");
    }

    /**
     * @param $id
     * @return Countries|Model
     */
    public static function findFirstByTourvisorId($id)
    {
        $country = self::findFirst("tourvisorId = '$id'");
        if (!$country) {
            $country = new Countries();
            $country->tourvisorId = $id;
            $country->save();
        }
        return $country;
    }
}
