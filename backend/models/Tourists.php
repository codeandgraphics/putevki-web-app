<?php

namespace Backend\Models;

use Phalcon\Mvc\Model,
	Phalcon\Mvc\Model\Behavior\Timestampable,
	Phalcon\Mvc\Model\Behavior\SoftDelete,
	Phalcon\Mvc\Model\Query;

class Tourists extends Model
{
	const DELETED = 'Y';
	const NOT_DELETED = 'N';

	public $id;
	public $manager_id;
	public $name;
	public $surname;
	public $patronymic;
	public $phone;
	public $address;
	public $email;
	public $passport_name;
	public $passport_surname;
	public $passport_number;
	public $passport_endDate;
	public $passport_issued;
	public $birthDate;
	public $nationality;
	public $discount;
	public $gender;
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

		$this->hasMany('id', 'Backend\Models\RequestTourists', 'touristId', [
			'alias' => 'requestTourists'
		]);
	}

	public function beforeSave()
	{
		if($this->birthDate)
		{
			$date = \DateTime::createFromFormat('d.m.Y', $this->birthDate);
			if($date) $this->birthDate = $date->format('Y-m-d');
		}
		if($this->passport_endDate)
		{
			$endDate = \DateTime::createFromFormat('d.m.Y', $this->passport_endDate);
			if($endDate) $this->passport_endDate = $endDate->format('Y-m-d');
		}
	}

	public function afterFetch()
	{
		if($this->birthDate)
		{
			$date = \DateTime::createFromFormat('Y-m-d', $this->birthDate);
			if($date) $this->birthDate = $date->format('d.m.Y');
		}
		if($this->passport_endDate)
		{
			$endDate = \DateTime::createFromFormat('Y-m-d', $this->passport_endDate);
			if($endDate) $this->passport_endDate = $endDate->format('d.m.Y');
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

	public static function addOrUpdate($tourist)
	{
		/*$query = "SELECT * FROM \Backend\Models\Tourists
						WHERE \Backend\Models\Tourists.passport_number = :passport_number:
						AND \Backend\Models\Tourists.passport_surname = :passport_surname:
						AND \Backend\Models\Tourists.passport_name = :passport_name:
						LIMIT 1";*/

		$touristModel = Tourists::query()
			->where('passport_number = :passport_number:')
			->andWhere('passport_surname = :passport_surname:')
			->andWhere('passport_name = :passport_name:')
			->bind([
				"passport_number" => $tourist->passport_number,
				"passport_name" => $tourist->passport_name,
				"passport_surname" => $tourist->passport_surname
			])
			->execute()
			->getFirst();


		if(!$touristModel)
		{
			$touristModel = new Tourists();

			$touristModel->passport_number	= $tourist->passport_number;
			$touristModel->passport_surname	= $tourist->passport_surname;
			$touristModel->passport_name	= $tourist->passport_name;
			$touristModel->passport_issued	= $tourist->passport_issued;
			$touristModel->passport_endDate	= $tourist->passport_endDate;
			$touristModel->birthDate		= $tourist->birthDate;
			$touristModel->phone			= $tourist->phone;
			$touristModel->email			= $tourist->email;
			$touristModel->gender			= $tourist->gender;
			$touristModel->nationality		= $tourist->nationality;


			if(!$touristModel->save())
			{
				foreach ($touristModel->getMessages() as $message) {
					print_r($message);
				}

			}

		}

		return $touristModel;
	}


}