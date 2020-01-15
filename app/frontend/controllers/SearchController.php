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
        $searchId = $searchQuery->run();

        $title =
            'Путевки и туры ' .
            $params->search->fromEntity()->name .
            ' &ndash; ' .
            $params->search->whereHumanized() .
            ' по ценам ниже чем у туроператора на ';

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
            'searchId' => $searchId,
            'params' => $params,
            'departures' => $departures,
            'title' => $title,
            'page' => 'search'
        ]);
    }

    public function shortAction()
    {
        $from = $this->dispatcher->getParam('from', 'string');
        $where = $this->dispatcher->getParam('where', 'string');

        $params = Params::getInstance();

        $params->search->fromFromQuery($from);
        $params->search->whereFromQuery($where, null);

        $params->store();

        $searchQuery = new SearchQuery();
        $searchQuery->fromParams($params->search);
        $searchId = $searchQuery->run();

        $title =
            'Путевки и туры ' .
            $params->search->fromEntity()->name .
            ' &ndash; ' .
            $params->search->whereHumanized() .
            ' по ценам ниже чем у туроператора на ';

        $departures = Tourvisor\Departures::find([
            'id NOT IN (:moscowId:, :spbId:, :noId:)',
            'bind' => [
                'moscowId' => 1,
                'spbId' => 5,
                'noId' => 99
            ],
            'order' => 'name'
        ]);

        $this->view->pick('search/index');

        $this->view->setVars([
            'searchId' => $searchId,
            'params' => $params,
            'departures' => $departures,
            'title' => $title,
            'page' => 'search'
        ]);
    }

    public function hotelAction()
    {
        $params = Params::getInstance();
        $params->search->fromDispatcher($this->dispatcher);
        $params->search->where->hotels = 0;
        $params->store();

        $searchQuery = new SearchQuery();
        $searchQuery->fromParams($params->search);
        $searchQuery->run();

        $hotelName = $this->dispatcher->getParam('hotelName', 'string');
        $hotelId = $this->dispatcher->getParam('hotelId', 'int');

        $response = new Response();

        $url = $this->url->get(
            'hotel/' . $hotelName . '-' . $hotelId . '#tours'
        );

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
        $params->search->where->hotels = 0;

        $params->store();

        $url = $params->search->buildQueryString();

        $response->setHeader('Location', $this->url->get('search/' . $url));

        return $response;
    }
}
