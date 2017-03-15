<?php
namespace Models\Api\Entities;


class Rating {
	public $id;
	public $name;

	public function __construct($id = null, $name = null)
	{
		if($id) {
			$this->id = $id;
		}
		if($name) {
			$this->name = $name;
		}
	}
}