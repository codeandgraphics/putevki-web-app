<?php

namespace Backend\Models;

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

	const ORIGIN_WEB = 'web';
	const ORIGIN_ANDROID = 'android';
	const ORIGIN_IOS = 'ios';
	const ORIGIN_MOBILE = 'mobile';

	public $id;

	public $manager_id;

	public $hotelName;
	public $hotelCountry;
	public $hotelRegion;
	public $hotelDate;
	public $hotelNights = 0;
	public $hotelPlacement;
	public $hotelMeal;
	public $hotelRoom;

	public $subjectSurname;
	public $subjectName;
	public $subjectPatronymic;
	public $subjectAddress;
	public $subjectPhone;
	public $subjectEmail;

	public $flightToNumber;
	public $flightToDepartureDate;
	public $flightToDepartureTime;
	public $flightToDepartureTerminal;
	public $flightToArrivalDate;
	public $flightToArrivalTime;
	public $flightToArrivalTerminal;
	public $flightToCarrier;
	public $flightToPlane;
	public $flightToClass;

	public $flightFromNumber;
	public $flightFromDepartureDate;
	public $flightFromDepartureTime;
	public $flightFromDepartureTerminal;
	public $flightFromArrivalDate;
	public $flightFromArrivalTime;
	public $flightFromArrivalTerminal;
	public $flightFromCarrier;
	public $flightFromPlane;
	public $flightFromClass;

	public $price;
	public $departureId;
	public $tourOperatorId;
	public $tourOperatorLink;
	public $requestStatusId;

	public $comment;

	public $branch_id;

	public $origin;

	public $creationDate;
	public $deleted = Tourists::NOT_DELETED;

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

		$this->hasOne('manager_id', Users::name(), 'id', [
			'alias' => 'manager'
		]);

		$this->hasOne('branch_id', Branches::name(), 'id', [
			'alias' => 'branch'
		]);

		$this->hasMany('id', Payments::name(), 'requestId', [
			'alias' => 'payments'
		]);
	}

	public function beforeSave()
	{
		$fields = [
			'hotelDate',
			'flightToDepartureDate',
			'flightToArrivalDate',
			'flightFromDepartureDate',
			'flightFromArrivalDate'
		];

		foreach ($fields as $field) {
			$date = \DateTime::createFromFormat('d.m.Y', $this->$field);
			$this->$field = $date ? $date->format('Y-m-d') : null;
		}

		$this->hotelNights = $this->hotelNights ?: 0;
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
		$fields = [
			'hotelDate',
			'flightToDepartureDate',
			'flightToArrivalDate',
			'flightFromDepartureDate',
			'flightFromArrivalDate'
		];

		foreach ($fields as $field) {
			$date = \DateTime::createFromFormat('Y-m-d', $this->$field);
			$this->$field = $date ? $date->format('d.m.Y') : null;
		}
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

	public function getDate()
	{
		return date('d.m.Y', strtotime($this->creationDate));
	}

	public function getNumber()
	{
		return $this->id . date('dmy', strtotime($this->creationDate));
	}
}