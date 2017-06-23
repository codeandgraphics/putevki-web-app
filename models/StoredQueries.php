<?php

namespace Models;

use Phalcon\Di;

class StoredQueries extends BaseModel {

	const DELAY_TIME = 600;

	public $id;

	public $fromId;

	public $whereCountry;
	public $whereRegions;
	public $whereHotels;

	public $whenDateFrom;
	public $whenDateTo;
	public $whenNightsFrom;
	public $whenNightsTo;

	public $peopleAdults;
	public $peopleChildren;

	public $filtersStars;
	public $filtersMeal;
	public $filtersRating;
	public $filtersOperator;

	public $searchId;
	public $date;

	public function initialize() {
		$this->setSource('stored_queries');
	}

	public static function store(SearchQuery $query, $searchId) {
		$searchQuery = new StoredQueries();
		$searchQuery->fromId = $query->from;

		$searchQuery->whereCountry = $query->where->country;
		$searchQuery->whereRegions = $query->where->getRegionsString();
		$searchQuery->whereHotels = $query->where->hotels;

		$searchQuery->whenDateFrom = $query->when->getDbDateFrom();
		$searchQuery->whenDateTo = $query->when->getDbDateTo();
		$searchQuery->whenNightsFrom = $query->when->nightsFrom;
		$searchQuery->whenNightsTo = $query->when->nightsTo;

		$searchQuery->peopleAdults = $query->people->adults;
		$searchQuery->peopleChildren = $query->people->getChildrenString();

		$searchQuery->filtersStars = $query->filters->stars;
		$searchQuery->filtersMeal = $query->filters->meal;
		$searchQuery->filtersRating = $query->filters->rating;
		$searchQuery->filtersOperator = $query->filters->operator;

		$searchQuery->date = Date::currentDbDateTime();

		$searchQuery->searchId = $searchId;

		$searchQuery->create();
	}

	public static function checkExists(SearchQuery $query) {
		$bind = [
			'from'              => $query->from,

			'whereCountry'      => $query->where->country,
			'whereRegions'      => $query->where->getRegionsString(),
			'whereHotels'       => $query->where->hotels,

			'whenDateFrom'      => $query->when->getDbDateFrom(),
			'whenDateTo'        => $query->when->getDbDateTo(),
			'whenNightsFrom'    => $query->when->nightsFrom,
			'whenNightsTo'      => $query->when->nightsTo,

			'peopleAdults'      => $query->people->adults,
			'peopleChildren'    => $query->people->getChildrenString(),

			'filtersStars'      => $query->filters->stars,
			'filtersMeal'       => $query->filters->meal,
			'filtersRating'     => $query->filters->rating,
			'filtersOperator'   => $query->filters->operator
		];

		$where = 'fromId = :from:' .
			' AND whereCountry = :whereCountry: AND whereRegions = :whereRegions: AND whereHotels = :whereHotels:' .
			' AND whenDateFrom = :whenDateFrom: AND whenDateTo = :whenDateTo:' .
			' AND whenNightsFrom = :whenNightsFrom: AND whenNightsTo = :whenNightsTo:' .
			' AND peopleAdults = :peopleAdults: AND peopleChildren = :peopleChildren:' .
			' AND filtersStars = :filtersStars: AND filtersMeal = :filtersMeal:' .
			' AND filtersRating = :filtersRating: AND filtersOperator = :filtersOperator:' .
			' AND searchId IS NOT NULL';

		$builder = Di::getDefault()->get('modelsManager')->createBuilder()
			->from(self::name())
			->where($where, $bind)
			->orderBy('date DESC');

		$existed = $builder->getQuery()->getSingleResult();

		if($existed && (time() - strtotime($existed->date)) <= self::DELAY_TIME) {
			return $existed->searchId;
		}

		return false;
	}
}