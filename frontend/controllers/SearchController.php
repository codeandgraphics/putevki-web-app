<?php

use Phalcon\Http\Response,
	Models\Tourvisor,
	Frontend\Models\SearchQueries;

class SearchController extends ControllerFrontend
{
	public function indexAction($from, $where, $date, $nights, $adults, $kids, $stars, $meal)
	{

		$params = new \stdClass();
		$params->from = $from;
		$params->where = $where;
		$params->date = $date;
		$params->nights = $nights;
		$params->adults = $adults;
		$params->kids = $kids;
		$params->stars = $stars;
		$params->meal = $meal;
		
		$searchQuery = new SearchQueries();
		$searchQuery->fillFromParams($params);
		$searchQuery->run();

		$meals = Tourvisor\Meals::find([
			'order' => 'id DESC'
		]);

		$title = 'Поиск путевок ' . $this->currentCity->departure->name .
			' &mdash; ' . $searchQuery->buildTitle() . ' на ';

		$this->view->setVars([
			'tourvisorId'	=> $searchQuery->tourvisorId,
			'params'		=> $searchQuery,
			'meals'			=> $meals,
			'title'			=> $title,
			'page'			=> 'search'
		]);
	}

	public function hotelAction($from, $where, $hotelName, $hotelId, $date, $nights, $adults, $kids, $stars, $meal)
	{
		$params = new \stdClass();
		$params->from = $from;
		$params->where = $where;
		$params->hotel = $hotelId;
		$params->date = $date;
		$params->nights = $nights;
		$params->adults = $adults;
		$params->kids = $kids;
		$params->stars = $stars;
		$params->meal = $meal;

		$searchQuery = new SearchQueries();
		$searchQuery->fillFromParams($params);
		$searchQuery->run();
		
		$response = new Phalcon\Http\Response();

		$url = $this->config->frontend->publicURL . 'hotel/' . $hotelName . '-' . $hotelId . '#tours';

		$response->setHeader('Location', $url);

		return $response;
	}

	public function hotelShortAction($from, $where, $hotelName, $hotelId)
	{
		$response = new Phalcon\Http\Response();

		$this->params->hotel = $hotelName . '-' . $hotelId;
		$this->params->departure = $from;
		$this->params->country = $where;

		$url = SearchQueries::buildQueryStringFromParams($this->params);

		$response->setHeader('Location', $this->config->frontend->publicURL . 'search/' . $url);

		return $response;
	}

	public function shortAction($from, $where)
	{
		$response = new Phalcon\Http\Response();

		$this->params->departure = $from;
		$this->params->country = $where;

		$url = SearchQueries::buildQueryStringFromParams($this->params);

		$response->setHeader('Location', $this->config->frontend->publicURL . 'search/' . $url);

		return $response;
	}
	
}
