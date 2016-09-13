<?php

namespace Backend\Models;

use Phalcon\Mvc\Model;

class RequestTourists extends Model
{
	const DELAY_TIME = 600;

	public $id;
	public $requestId;
	public $touristId;

	public function initialize()
	{
		$this->belongsTo('requestId', 'Backend\Models\Requests', 'id', [
			'alias' => 'request'
		]);

		$this->belongsTo('touristId', 'Backend\Models\Tourists', 'id', [
			'alias' => 'tourist'
		]);
	}

}