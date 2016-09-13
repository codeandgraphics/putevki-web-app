<?php

namespace Backend\Models;

use Phalcon\Mvc\Model,
	Phalcon\Mvc\Model\Behavior\Timestampable,
	Phalcon\Mvc\Model\Behavior\SoftDelete;

class TourOperators extends Model
{
	const DELETED = 'Y';
	const NOT_DELETED = 'N';

	public $id;
	public $name;
	public $legal;
	public $guarantee;
	public $creationDate = null;
	public $deleted = TourOperators::NOT_DELETED;

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
				'value' => TourOperators::DELETED
			)
		));
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



}