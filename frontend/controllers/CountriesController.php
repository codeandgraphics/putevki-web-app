<?php

namespace Frontend\Controllers;

use Frontend\Models\Params;
use Models\Countries;
use Models\Regions;
use Models\SearchQuery;
use Models\Tourvisor;

class CountriesController extends BaseController
{
	public function indexAction()
	{
		$this->view->setVars([
			'title'     => 'Куда поехать отдыхать? Все страны на ',
		]);
	}

	public function countryAction()
	{
		$countryUri = $this->dispatcher->getParam('country');

		$country = Countries::findFirstByUri($countryUri);

		if(!$country) {
			return $this->response->setStatusCode(404);
		}

		$builder = $this->modelsManager->createBuilder()
			->columns([
				'region.*',
				'tourvisor.*',
			])
			->addFrom(Tourvisor\Regions::name(), 'tourvisor')
			->leftJoin(
				Regions::name(),
				'region.tourvisorId = tourvisor.id',
				'region'
			)
			->where('tourvisor.countryId = :id: AND region.active = 1 AND region.hasInfo = 1', ['id' => $country->tourvisorId])
			->orderBy('tourvisor.name');

		$regions = $builder->getQuery()->execute();

		$params = Params::getInstance();

		$params->search->where->country = $country->tourvisorId;
		$params->search->where->regions = [];
		$params->search->where->hotels = 0;

		$departures = Tourvisor\Departures::find([
			'id NOT IN (:moscowId:, :spbId:, :noId:)',
			'bind' => [
				'moscowId' => 1,
				'spbId' => 5,
				'noId' => 99
			],
			'order' => 'name'
		]);

		$this->view->setVars([
			'title'         => $country->title . ' на ',
			'params'        => $params,
			'page'          => 'country',
			'departures'    => $departures,
			'country'       => $country,
			'regions'       => $regions,
			'meta'          => $country->getMeta()
		]);
	}

	public function regionAction()
	{
		$regionUri = $this->dispatcher->getParam('region');

		$region = Regions::findFirstByUri($regionUri);

		if(!$region) {
			return $this->response->setStatusCode(404);
		}

		$country = Countries::findFirstByTourvisorId($region->tourvisor->country->id);

		$params = Params::getInstance();

		$params->search->where->country = $country->tourvisorId;
		$params->search->where->regions = [$region->tourvisorId];
		$params->search->where->hotels = 0;

		$departures = Tourvisor\Departures::find([
			'id NOT IN (:moscowId:, :spbId:, :noId:)',
			'bind' => [
				'moscowId' => 1,
				'spbId' => 5,
				'noId' => 99
			],
			'order' => 'name'
		]);

		$this->view->setVars([
			'title'         => $region->title . ' на ',
			'params'        => $params,
			'page'          => 'country',
			'departures'    => $departures,
			'country'       => $country,
			'region'        => $region,
			'meta'          => $region->getMeta()
		]);
	}
}
