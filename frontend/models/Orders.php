<?php

namespace Models;


use Phalcon\Mvc\Model,
	Phalcon\Mvc\Model\Message,
	Phalcon\Mvc\Model\Relation;

class Orders extends Model
{	
	public $id;
	public $date = null;
	public $tour = null;
	public $status = 0;
	
	public function initialize()
	{
	}
	
	public function beforeUpdate()
    {
        $this->tour = serialize($this->tour);
    }

    public function afterFetch()
    {
        $this->tour = unserialize($this->tour);
    }
	
}