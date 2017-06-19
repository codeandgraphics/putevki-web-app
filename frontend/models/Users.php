<?php

namespace Models;


use Phalcon\Mvc\Model;
use Phalcon\Mvc\Model\Validator\InclusionIn;
use Phalcon\Mvc\Model\Validator\Uniqueness;
use Phalcon\Mvc\Model\Validator\Regex;
use Phalcon\Mvc\Model\Validator\Email as EmailValidator;
use Phalcon\Mvc\Model\Behavior\SoftDelete;
use Phalcon\Mvc\Model\Behavior\Timestampable;

class Users extends Model
{
	const SEX_MALE = 1;
	const SEX_FEMALE = 0;
	const SEX_UNDEFINED = -1;
	
	const DELETED = 'deleted';
	const NOT_DELETED = 'active';

	public $id;
	public $phone;
	public $password;
	
	public $name;
	public $email = null;
	public $sex = self::SEX_UNDEFINED;
	public $imageUrl;
	
	public $requestsCount = 0;
	public $smsCount = 0;
	public $messagesNotificationsEnabled = 0;
	public $commentsNotificationsEnabled = 0;
	
	private $creationDate;
	private $status;
	
	public function initialize()
	{	
		$this->addBehavior(new Timestampable(
			array(
				'beforeValidationOnCreate'	=> array(
					'field'			=> 'creationDate',
					'format'		=> 'Y-m-d H:i:s'
				)
			)
		));
		$this->addBehavior(new SoftDelete(
			array(
				'field' => 'status',
				'value' => self::DELETED
			)
		));
	}
	
	public function validation()
	{	
		$this->validate(new InclusionIn(array(
				'field'		=> 'sex',
				'domain'	=> array(self::SEX_MALE, self::SEX_FEMALE, self::SEX_UNDEFINED),
				'message'	=> 'Sex must be 1, 0 or -1'
			)
		));
		
		if($this->email)
		{
			$this->validate(new Uniqueness(array(
					'field'		=> 'email',
					'message'	=> 'E-mail must be unique'
				)
			));
			
			$this->validate(new EmailValidator(array(
					'field'		=> 'email',
					'message'	=> 'Wrong e-mail format'
				)
			));
		}
		
		$this->validate(new Uniqueness(array(
				'field'		=> 'phone',
				'message'	=> 'Phone must be unique'
			)
		));
		
		$this->validate(new Regex(array(
				'field'		=> 'phone',
				'pattern'	=> '/\+7[0-9]+/',
				'message'	=> 'Phone must be in format +7[0-9]+'
			)
		));
		
		if ($this->validationHasFailed() == true)
		{
			return false;
		}
	}
	
	public function afterFetch()
	{
		$this->id = (int) $this->id;
		$this->sex = (int) $this->sex;
		$this->requestCount = (int) $this->requestCount;
		$this->smsCount = (int) $this->smsCount;
		$this->messagesNotificationsEnabled = (int) $this->messagesNotificationsEnabled;
		$this->commentsNotificationsEnabled = (int) $this->commentsNotificationsEnabled;
	}
	
	public function beforeSave()
	{
		$this->sex = (int) $this->sex;
		
	}
	
	public function format()
	{	
		$user = new \stdClass();
		
		$user->id		= (int) $this->id;
		$user->email	= $this->email;
		$user->phone	= $this->phone;
		$user->name		= $this->name;
		$user->sex		= (int) $this->sex;
		$user->imageUrl	= $this->imageUrl;
		
		$user->requestsCount	= (int) $this->requestsCount;
		$user->smsCount			= (int) $this->smsCount;
		
		$user->messagesNotificationsEnabled	= (int) $this->messagesNotificationsEnabled;
		$user->commentsNotificationsEnabled	= (int) $this->commentsNotificationsEnabled;
		
		return $user;
	}
	
	public function formatPublic()
	{	
		$user = new \stdClass();
		
		$user->id		= (int) $this->id;
		$user->name		= $this->name;
		$user->sex		= (int) $this->sex;
		$user->imageUrl	= $this->imageUrl;
		
		return $user;
	}
	
}