<?php
namespace Models\Api\Entities;

class Where {

	public $country;
	public $regions = [];
	public $hotels;

	public function __construct($where = null)
	{
		if($where) {
			$this->country = (int) $where->country;

			if(is_array($where->regions)) {
				$this->regions = $where->regions;
			}
			if(property_exists($where->hotel, 'id')) {
				$this->hotels = $where->hotel->id;
			}
		}
	}

	public function fromStored($where = null) {
	    if($where) {
	        $this->country = $where->country ? : $this->country;
            $this->regions = $where->regions ? : [];
            $this->hotels = $where->hotels ? : $this->hotels;
        }
    }

    public function fromForm($where = null) {
		if($where) {
			$this->country = $where->country;
			$this->regions = $where->regions ? : [];
			$this->hotels = $where->hotels;
		}
    }
}