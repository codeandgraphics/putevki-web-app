<?php

namespace Models;

use Phalcon\Mvc\Model;
use Models\Tourvisor;

class Regions extends BaseModel
{
    public $tourvisorId;
    public $uri;
    public $title;
    public $preview;
    public $about;
    public $metaKeywords;
    public $metaDescription;
    public $popular;
    public $active;
    public $hasInfo;

    public function initialize()
    {
        $this->belongsTo('tourvisorId', Tourvisor\Regions::name(), 'id', [
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
     * @return Regions|Model
     */
    public static function findFirstByUri($uri)
    {
        return self::findFirst("uri = '$uri'");
    }

    /**
     * @param $id
     * @return Regions|Model
     */
    public static function findFirstByTourvisorId($id)
    {
        $region = self::findFirst("tourvisorId = '$id'");
        if (!$region) {
            $region = new Regions();
            $region->tourvisorId = $id;
            $region->save();
        }
        return $region;
    }
}
