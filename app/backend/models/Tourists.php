<?php

namespace Backend\Models;

use Models\BaseModel;
use Phalcon\Mvc\Model\Behavior\Timestampable;
use Phalcon\Mvc\Model\Behavior\SoftDelete;

class Tourists extends BaseModel
{
	const DELETED = 'Y';
	const NOT_DELETED = 'N';

	public $id;
	public $managerId;
	public $name;
	public $surname;
	public $patronymic;
	public $phone;
	public $address;
	public $email;
	public $passportName;
	public $passportSurname;
	public $passportNumber;
	public $passportEndDate;
	public $passportIssued;
	public $birthDate;
	public $nationality;
	public $discount;
	public $gender;
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
				'value' => Tourists::DELETED
			)
		));

		$this->hasMany('id', RequestTourists::name(), 'touristId', [
			'alias' => 'requestTourists'
		]);
	}

	public function beforeSave()
	{
		if ($this->birthDate) {
			$date = \DateTime::createFromFormat('d.m.Y', $this->birthDate);
			if ($date) $this->birthDate = $date->format('Y-m-d');
		}
		if ($this->passportEndDate) {
			$endDate = \DateTime::createFromFormat('d.m.Y', $this->passportEndDate);
			if ($endDate) $this->passportEndDate = $endDate->format('Y-m-d');
		}
	}

	public function afterFetch()
	{
		if ($this->birthDate) {
			$date = \DateTime::createFromFormat('Y-m-d', $this->birthDate);
			if ($date) $this->birthDate = $date->format('d.m.Y');
		}
		if ($this->passportEndDate) {
			$endDate = \DateTime::createFromFormat('Y-m-d', $this->passportEndDate);
			if ($endDate) $this->passportEndDate = $endDate->format('d.m.Y');
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

	public static function addOrUpdate($tourist)
	{
		$touristModel = Tourists::query()
			->where('passportNumber = :passportNumber:')
			->andWhere('passportSurname = :passportSurname:')
			->andWhere('passportName = :passportName:')
			->bind([
				'passportNumber' => $tourist->passportNumber,
				'passportName' => $tourist->passportName,
				'passportSurname' => $tourist->passportSurname
			])
			->execute()
			->getFirst();


		if (!$touristModel) {
			$touristModel = new Tourists();

			$touristModel->passportNumber = $tourist->passportNumber;
			$touristModel->passportSurname = $tourist->passportSurname;
			$touristModel->passportName = $tourist->passportName;
			$touristModel->passportIssued = $tourist->passportIssued;
			$touristModel->passportEndDate = $tourist->passportEndDate;
			$touristModel->birthDate = $tourist->birthDate;
			$touristModel->phone = $tourist->phone;
			$touristModel->email = $tourist->email;
			$touristModel->gender = $tourist->gender;
			$touristModel->nationality = $tourist->nationality;


			if (!$touristModel->save()) {
				foreach ($touristModel->getMessages() as $message) {
					print_r($message);
				}

			}

		}

		return $touristModel;
	}


}