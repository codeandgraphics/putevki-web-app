<?php

namespace Backend\Models;

use Models\BaseModel;
use Models\Branches;
use Phalcon\Mvc\Model\Validator\Uniqueness;
use Phalcon\Mvc\Model\Behavior\Timestampable;
use Phalcon\Mvc\Model\Behavior\SoftDelete;

class Users extends BaseModel
{
	const DELETED = 'Y';
	const NOT_DELETED = 'N';

	const ROLE_GUEST = 'Guest';
	const ROLE_MANAGER = 'Manager';
	const ROLE_ADMIN = 'Admin';

	public $id;
	public $name;
	public $email;
	public $password;
	public $role;
	public $company;
	public $imageUrl;
	public $lastLogin = null;
	public $creationDate = null;
	public $deleted = Users::NOT_DELETED;
	public $branch_id = null;

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
				'value' => Users::DELETED
			)
		));

		$this->hasOne('id', Branches::name(), 'manager_id', [
			'alias' => 'branch'
		]);
	}

	public function validation()
	{
		$this->validate(new Uniqueness(
			array(
				'field' => 'email',
				'message' => 'E-mail пользователя должен быть уникальным'
			)
		));

		return $this->validationHasFailed() !== true;
	}

}