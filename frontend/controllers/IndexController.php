<?php

namespace Frontend\Controllers;

use Backend\Models\Requests;
use Frontend\Models\Params;
use Models\Tourvisor\Departures	as TourvisorDepartures;
use Models\Cities;
use Frontend\Models\SearchQueries;
use Mobile_Detect;
use Phalcon\Db;

class IndexController extends BaseController
{
	public function indexAction()
	{
		$regions = $this->db->fetchAll('
			SELECT r.id AS id, r.name AS name, c.name AS country_name, c.id as country_id
			FROM search_queries AS s
			INNER JOIN tourvisor_regions AS r ON s.regionId = r.id
            INNER JOIN tourvisor_countries AS c ON s.countryId = c.id AND active = 1
			WHERE s.queryDate > (NOW() - INTERVAL 1 MONTH)
			GROUP BY s.regionId
			ORDER BY COUNT(s.id) DESC
			LIMIT 6
		', Db::FETCH_OBJ);

		$popularItems = [];
		$popularCountries = [];

		foreach($regions as $region)
		{
			$pop = new \stdClass();

			$pop->country = $region->country_name;
			$pop->region = $region->name;
			$pop->regionId = $region->id;
			$pop->countryId = $region->country_id;
			
			$par = $this->params;
			$par->departure = $this->currentCity->departure->name;
			$par->country = $pop->country;
			$par->region = $pop->region;
			
			$pop->url = '/search/'.SearchQueries::buildQueryStringFromParams($par);
			
			$popularItems[] = $pop;
			$popularCountries[] = $pop->country;
		}

		$departures = TourvisorDepartures::find([
			'id NOT IN (:moscowId:, :spbId:, :noId:)',
			'bind' => [
				'moscowId'	=> 1,
				'spbId'		=> 5,
				'noId'		=> 99
			],
			'order'	=> 'name'
		]);

		$add = '';
		if($this->city->name_rod !== $this->city->departure->name_from)
		{
			$add = ' с вылетом из ' . $this->city->departure->name_from;
		}

		$title = 'Туры из ' . $this->city->name_rod . $add . ' на ';

		$this->view->setVars([
			'populars'			=> $popularItems,
			'popularCountries'	=> implode(', ', $popularCountries),
			'departures'		=> $departures,
			'params'			=> $this->params,
			'title'				=> $title,
			'page'				=> 'main'
		]);
	}

	public function cityAction() {
	    $this->view->disable();
        $cityUri = $this->dispatcher->getParam('city');

        $params = Params::getInstance();
	    $city = Cities::findFirstByUri($cityUri);
	    if($city) {
            $params->city = (int) $city->id;
            $params->searchParams->from = (int) $city->flight_city;
        }
        $params->store();

        $this->response->redirect('');
    }

	public function agreementAction()
	{
		$this->view->disable();

		if($this->request->has('mobile')) {
			echo $this->simpleView->render('index/agreement');
		} else {
			$pdf = new \mPDF('BLANK', 'A4', 8, 'utf-8', 8, 8, 20, 20, 0, 0);

			$request = new Requests();

			$this->simpleView->setVar('req', $request);
			$this->simpleView->setVar('assetsUrl', $this->config->frontend->publicURL . 'assets');
			$html = $this->simpleView->render('requests/pdf/agreement');
			$css = file_get_contents(APP_PATH . '/backend/views/requests/pdf/style.css');

			$header = $this->simpleView->render('requests/pdf/header');
			$footer = $this->simpleView->render('requests/pdf/footer');

			$pdf->WriteHTML($css, 1);
			$pdf->SetHTMLHeader($header);
			$pdf->SetHTMLFooter($footer);
			$pdf->WriteHTML($html, 2);

			$pdf->Output('agreement-'.$request->getNumber().'.pdf', 'I');
		}
	}

	public function appAction() {
		$this->view->disable();
		$detect = new Mobile_Detect();

		if($detect->isiOS()) {
			$this->response->redirect($this->config->appStore);
		} else if($detect->isAndroidOS()) {
			$this->response->redirect($this->config->googlePlay);
		} else {
			$this->response->redirect('/');
		}

	}

	public function unitellerAction()
	{
		$this->view->setVars([
			'title' => 'Онлайн-оплата Uniteller',
			'page' => 'uniteller'
		]);
	}

	public function robotsAction()
	{
		$this->view->disable();

		//TODO: remove in production
		echo 'User-agent: *' . "\n";
		echo 'Disallow: /' . "\n";
	}
	

	
}
