<?php

namespace Frontend\Controllers;

use Frontend\Models\Params;
use Phalcon\Http\Response;
use Models\Origin;
use Models\Tourvisor;
use Models\SearchQuery;
use Models\StoredQueries;

class SearchController extends BaseController
{
	public function indexAction()
	{
		$params = Params::getInstance();
		$params->search->fromDispatcher($this->dispatcher);
		$params->store();

		$searchQuery = new SearchQuery();
		$searchQuery->fromParams($params->search);
		$searchId = $searchQuery->run(Origin::WEB);

		$meals = Tourvisor\Meals::find([
			'order' => 'id DESC'
		]);

		$title = 'Поиск путевок ' . $params->search->fromEntity()->name .
			' &mdash; ' . ' на ';

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
			'tourvisorId' => $searchId,
			'params' => $params,
			'meals' => $meals,
			'departures' => $departures,
			'title' => $title,
			'page' => 'search'
		]);
	}

	public function hotelAction($from, $where, $hotelName, $hotelId, $date, $nights, $adults, $kids, $stars, $meal)
	{
        $params = Params::getInstance();
        $params->search->fromDispatcher($this->dispatcher);
        //$params->store();

		$searchQuery = new StoredQueries();
		$searchQuery->fillFromParams($params);
		$searchQuery->run();

		$response = new Response();

		$url = $this->url->get('hotel/' . $hotelName . '-' . $hotelId . '#tours');

		$response->setHeader('Location', $url);

		return $response;
	}

	public function hotelShortAction()
	{
		$from = $this->dispatcher->getParam('from', 'string');
		$where = $this->dispatcher->getParam('where', 'string');
		$hotelId = $this->dispatcher->getParam('hotelId', 'int');

		$response = new Response();

		$params = Params::getInstance();

		$params->search->fromFromQuery($from);
		$params->search->whereFromQuery($where, $hotelId);

		$params->store();

		$url = $params->search->buildQueryString();

		$response->setHeader('Location', $this->url->get('search/' . $url));

		return $response;
	}

	public function shortAction()
	{
		$from = $this->dispatcher->getParam('from', 'string');
		$where = $this->dispatcher->getParam('where', 'string');

		$response = new Response();

		$params = Params::getInstance();

		$params->search->fromFromQuery($from);
		$params->search->whereFromQuery($where, null);

		$params->store();

		$url = $params->search->buildQueryString();

		$response->setHeader('Location', $this->url->get('search/' . $url));

		return $response;
	}

}
