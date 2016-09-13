<?php

namespace Backend\Models;

use Phalcon\Mvc\Model,
	Phalcon\Mvc\Model\Behavior\Timestampable,
	Phalcon\Mvc\Model\Behavior\SoftDelete,
	Utils\Email\Mailgun;

class Requests extends Model
{
	const DELETED = 'Y';
	const NOT_DELETED = 'N';

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

	public $creationDate = null;
	public $deleted = Tourists::NOT_DELETED;

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

		$this->addBehavior(new SoftDelete(
			array(
				'field' => 'deleted',
				'value' => Tourists::DELETED
			)
		));

		$this->hasMany('id', 'Backend\Models\RequestTourists', 'requestId', [
			'alias' => 'tourists'
		]);

		$this->hasOne('departureId', 'Models\Tourvisor\Departures', 'id', [
			'alias'	=> 'departure'
		]);

		$this->hasOne('tourOperatorId', 'Models\Tourvisor\Operators', 'id', [
			'alias'	=> 'tourOperator'
		]);

		$this->hasOne('requestStatusId', 'Backend\Models\RequestStatuses', 'id', [
			'alias'	=> 'status'
		]);

		$this->hasOne('manager_id', 'Backend\Models\Users', 'id', [
			'alias'	=> 'manager'
		]);

		$this->hasOne('branch_id', 'Models\Branches', 'id', [
			'alias'	=> 'branch'
		]);

		$this->hasMany('id', 'Backend\Models\Payments', 'requestId', [
			'alias' => 'payments'
		]);

		/*$this->hasManyToMany('id',
			'Models\RequestTourists',
			'requestId', 'touristId',
			'Models\Tourists',
			'id'
		);*/
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

		foreach($fields as $field)
		{
			$date = \DateTime::createFromFormat('d.m.Y', $this->$field);
			$this->$field = ($date) ? $date->format('Y-m-d') : null;
		}

		$this->hotelNights = ($this->hotelNights) ? : 0;
	}

	public function afterSave()
	{
		if($this->price > 0)
		{
			$payment = Payments::findFirst('requestId = ' . $this->id);
			if(!$payment)
			{
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

	public function getPaid()
	{
		$paid = 0;

		foreach($this->payments as $payment)
		{
			if($payment->status == 'authorized' || $payment->status == 'paid')
			{
				$paid += $payment->sum;
			}
		}

		return $paid;
	}

	public function getSum()
	{
		$sum = 0;

		foreach($this->payments as $payment)
		{
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

		foreach($fields as $field)
		{
			$date = \DateTime::createFromFormat('Y-m-d', $this->$field);
			$this->$field = ($date) ? $date->format('d.m.Y') : null;
		}
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


	public static function statuses($status)
	{
		$statuses = [
			'new'		=> 'Новая',
			'booked'	=> 'Забронирована',
			'check'		=> 'Проверка',
			'refuse'	=> 'Отказ',
			'approve'	=> 'Подтверждена'
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