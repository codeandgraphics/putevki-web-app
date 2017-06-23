<?php

namespace Models\Entities;

class Where
{
	const REGIONS_SEPARATOR = ',';

	public $country;
	public $regions = [];
	public $hotels = 0;

	public function __construct($where = null)
	{
		if ($where) {
			$this->country = (int)$where->country;

			if (is_array($where->regions)) {
				$this->regions = $where->regions;
			}
			if (property_exists($where->hotel, 'id')) {
				$this->hotels = $where->hotel->id;
			}
		}
	}

	public function fromStored($where = null)
	{
		if ($where) {
			if (count($where->regions) > 0) {
				sort($where->regions);
				$this->regions = $where->regions;
			} else {
				$this->regions = [];
			}
			$this->country = $where->country ?: $this->country;
			$this->hotels = $where->hotels > 0 ? $where->hotels : $this->hotels;
		}
	}

	public function fromForm($where = null)
	{
		if ($where) {
			$this->country = $where->country;
			$this->hotels = $where->hotels > 0 ? $where->hotels : 0;

			if (count($where->regions) > 0) {
				sort($where->regions);
				$this->regions = $where->regions;
			} else {
				$this->regions = [];
			}
		}
	}

	public function getRegionsString()
	{
		if (count($this->regions) === 0) {
			return null;
		}
		return implode(self::REGIONS_SEPARATOR, $this->regions);
	}
}