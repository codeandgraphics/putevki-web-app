<?php

namespace Models;

use Phalcon\Mvc\Model;

class RuntimeError extends Model
{
	public function initialize()
	{
		$this->setSource('runtimeError');
	}
}
