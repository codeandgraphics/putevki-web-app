<?php

namespace Models\Api;

use Models\BaseModel;

class MobileSearchQuery extends BaseModel
{
	const DELAY_TIME = 600;

	public $id;
	public $query;
	public $searchId;
	public $date;

	public static function checkExists(SearchQuery $query) {
		$searchQuery = json_encode($query);

		$existed = self::findFirst("query = '$searchQuery'");

		if($existed || (time() - strtotime($existed->date)) <= self::DELAY_TIME) {
			return $existed->searchId;
		}

		return false;
	}

}