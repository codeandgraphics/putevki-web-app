<?php

namespace Models;


use Phalcon\Mvc\Model;

class Orders extends Model
{	
	public $id;
	public $date;
	public $tour;
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