<?php

namespace Models;

use \Phalcon\Mvc\Model;

class BaseModel extends Model
{
	public static function name(){
		return get_called_class();
	}
}