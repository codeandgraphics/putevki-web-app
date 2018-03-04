<?php

namespace Models\Tourvisor;

use Interfaces\ITourvisorEntity;
use Models\BaseModel;
use Phalcon\Mvc\Model;

class Stars extends BaseModel implements ITourvisorEntity
{

	public $id;
	public $name;

	public function initialize()
	{
		$this->setSource('tourvisor_stars');
	}

	public function fromTourvisor($item)
	{
		$this->id = $item->id;
		$this->name = $item->name;
	}

	public function format()
	{
		$star = new \stdClass();

		$star->id = $this->id;
		$star->name = $this->name;

		return $star;
	}

	/**
	 * @param null $parameters
	 * @return Stars|Model
	 */
	public static function findFirst($parameters = null)
	{
		return parent::findFirst($parameters);
	}

	/**
	 * @param mixed $parameters
	 * @return Stars[]|Model\ResultsetInterface
	 */
	public static function find($parameters = null)
	{
		return parent::find($parameters);
	}

}