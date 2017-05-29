<?php

namespace Backend\Models;

use Phalcon\Di;
use Phalcon\Mvc\Model,
	Phalcon\Mvc\Model\Behavior\Timestampable;

class Payments extends Model
{

	public $id;
	public $requestId;
	public $sum;
	public $payDate;
	public $status;
	public $approval_code;
	public $bill_number;
	public $creationDate;

	public function initialize()
	{
		$this->addBehavior(new Timestampable(
			array(
				'beforeCreate'  => array(
					'field'     => 'creationDate',
					'format'    => 'Y-m-d H:i:s'
				)
			)
		));

		$this->belongsTo('requestId', 'Backend\Models\Requests', 'id', array(
			'alias' => 'request'
		));
	}

	public function beforeSave()
	{
		if($this->status == 'authorized' || $this->status == 'paid')
		{
			//Send Email
			// Utils\Email::send
		}
	}

	public function isSuccess() {
		return ($this->status === 'authorized' || $this->status === 'paid');
	}

	public function getOrder() {
		return Di::getDefault()->get('config')->frontend->uniteller->orderPrefix . $this->id;
	}
}