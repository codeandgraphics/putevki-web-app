<?php

namespace Backend\Models;

use Models\Origin;
use Models\BaseModel;
use Models\Branches;
use Models\Tourvisor\Departures;
use Models\Tourvisor\Operators;
use Phalcon\Mvc\Model\Behavior\Timestampable;
use Phalcon\Mvc\Model\Behavior\SoftDelete;

class Requests extends BaseModel
{
	const DELETED = 'Y';
	const NOT_DELETED = 'N';

	const ORIGIN_WEB = Origin::WEB;
	const ORIGIN_ANDROID = Origin::MOBILE_ANDROID;
	const ORIGIN_IOS = Origin::MOBILE_IOS;
	const ORIGIN_MOBILE = Origin::MOBILE;

	public $id;

	public $managerId;

	public $flightsTo;
	public $flightsFrom;
	public $hotel;

	public $subjectSurname;
	public $subjectName;
	public $subjectPatronymic;
	public $subjectAddress;
	public $subjectPhone;
	public $subjectEmail;

	public $price;
	public $departureId;
	public $tourOperatorId;
	public $tourOperatorLink;
	public $requestStatusId;

	public $comment;

	public $branchId;

	public $origin;

	public $creationDate;
	public $deleted = Tourists::NOT_DELETED;

	private $_flightsTo;
	private $_flightsFrom;
	private $_hotel;

	public function initialize()
	{
		$this->addBehavior(new Timestampable(
			array(
				'beforeCreate' => array(
					'field' => 'creationDate',
					'format' => 'Y-m-d H:i:s'
				)
			)
		));

		$this->addBehavior(new SoftDelete(
			array(
				'field' => 'deleted',
				'value' => Requests::DELETED
			)
		));

		$this->hasMany('id', RequestTourists::name(), 'requestId', [
			'alias' => 'tourists'
		]);

		$this->hasOne('departureId', Departures::name(), 'id', [
			'alias' => 'departure'
		]);

		$this->hasOne('tourOperatorId', Operators::name(), 'id', [
			'alias' => 'tourOperator'
		]);

		$this->hasOne('requestStatusId', RequestStatuses::name(), 'id', [
			'alias' => 'status'
		]);

		$this->hasOne('managerId', Users::name(), 'id', [
			'alias' => 'manager'
		]);

		$this->hasOne('branchId', Branches::name(), 'id', [
			'alias' => 'branch'
		]);

		$this->hasMany('id', Payments::name(), 'requestId', [
			'alias' => 'payments'
		]);
	}

	public function afterSave()
	{
		if ($this->price > 0) {
			$payment = Payments::findFirst('requestId = ' . $this->id);
			if (!$payment) {
				$payment = new Payments();
				$payment->requestId = $this->id;
			}
			$payment->sum = $this->price;
			$payment->save();
		}
	}

	public function afterCreate()
	{
	}

	public static function getDeleted()
	{
		return 'deleted';
	}

	public function getPaid()
	{
		$paid = 0;

		foreach ($this->payments as $payment) {
			if ($payment->status === 'authorized' || $payment->status === 'paid') {
				$paid += $payment->sum;
			}
		}

		return $paid;
	}

	public function getSum()
	{
		$sum = 0;

		foreach ($this->payments as $payment) {
			$sum += $payment->sum;
		}

		return $sum;
	}

	public function afterFetch()
	{
		$this->_flightsTo = json_decode($this->flightsTo);
		$this->_flightsFrom = json_decode($this->flightsFrom);
		$this->_hotel = json_decode($this->hotel);
	}

	public function getMessages($filter = null)
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


	public static function statuses($status)
	{
		$statuses = [
			'new' => 'Новая',
			'booked' => 'Забронирована',
			'check' => 'Проверка',
			'refuse' => 'Отказ',
			'approve' => 'Подтверждена'
		];

		return $statuses[$status];
	}

	public function setFlights($direction, $flights) {
		if($direction === 'To') {
			$this->flightsTo = json_encode($flights);
		}
		$this->flightsFrom = json_encode($flights);
	}

	public function setHotel($hotel) {
		$this->hotel = json_encode($hotel);
	}

	public function getDate() : string
	{
		return date('d.m.Y', strtotime($this->creationDate));
	}

	public function getNumber() : string
	{
		return $this->id . date('dmy', strtotime($this->creationDate));
	}

	public function hasFlights() {
		return (count($this->_flightsTo) > 0 && count($this->_flightsFrom) > 0);
	}

	public function getFlights($direction){
		if($direction === 'To') {
			return $this->_flightsTo;
		}
		return $this->_flightsFrom;
	}

	public function getHotel(){
		return $this->_hotel;
	}
}