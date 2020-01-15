<?php

namespace Backend\Models;

use Models\BaseModel;

class RequestTourists extends BaseModel
{
    const DELAY_TIME = 600;

    public $id;
    public $requestId;
    public $touristId;

    public function initialize()
    {
        $this->belongsTo('requestId', Requests::name(), 'id', [
            'alias' => 'request'
        ]);

        $this->belongsTo('touristId', Tourists::name(), 'id', [
            'alias' => 'tourist'
        ]);
    }
}
