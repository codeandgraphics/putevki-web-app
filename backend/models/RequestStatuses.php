<?php

namespace Backend\Models;

use Phalcon\Mvc\Model;

class RequestStatuses extends Model
{
	public $id;
	public $key;
	public $name;
	public $class;
}