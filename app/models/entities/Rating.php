<?php

namespace Models\Entities;

class Rating
{
	public $id;
	public $name;

	public function __construct($id = null, $name = null)
	{
		if ($id >= 0) {
			$this->id = $id;
		}
		if ($name) {
			$this->name = $name;
		}
	}
}