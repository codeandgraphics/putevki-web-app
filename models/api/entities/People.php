<?php
namespace Models\Api\Entities;

class People {

	public $adults;
	public $children = [];


	public function __construct($people = null)
	{
		if($people) {
			$this->adults = (int) $people->adults;

			if(is_array($people->children)) {
				$this->children = $people->children;
			}
		}
	}
}