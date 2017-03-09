<?php
namespace Models\Api\Entities;

class Where {

	public $country;
	public $regions = [];
	public $hotels = [];

	public function __construct($where = null)
	{
		if($where) {
			$this->country = (int) $where->country;

			if(is_array($where->regions)) {
				$this->regions = $where->regions;
			}
			if(is_array($where->hotels)) {
				$this->hotels = $where->hotels;
			}
		}
	}
}