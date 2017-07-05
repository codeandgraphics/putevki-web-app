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
			->where('tourvisor.countryId = :id: AND region.active = 1', ['id' => $country->tourvisorId])
			->orderBy('tourvisor.name');

		$regions = $builder->getQuery()->execute();

		$params = Params::getInstance();

		$params->search->where->country = $country->tourvisorId;
		$params->search->where->regions = [];
		$params->search->where->hotels = 0;

		$this->view->setVars([
			'params'    => $params,
			'page'      => 'country',
			'country'   => $country,
			'regions'   => $regions,
		]);
	}
}
