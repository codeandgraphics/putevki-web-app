<?php

use Phalcon\Http\Response			as Response,
	Models\Tourvisor				as Tourvisor,
	Models\Tourvisor\Departures		as TourvisorDepartures,
	Models\Cities					as Cities,
	Frontend\Models\SearchQueries	as SearchQueries,
	Frontend\Models\Populars		as Populars;
use Mobile_Detect;

class IndexController extends ControllerFrontend
{
	public function indexAction($city = 0)
	{
		if($city !== '')
		{
			Cities::checkCity($city);

			$this->response->redirect('/');
		}

		$this->view->setVar('currentCity', $this->currentCity);

		$regions = $this->db->fetchAll('
			SELECT r.id AS id, r.name AS name, c.name AS country_name, c.id as country_id
			FROM search_queries AS s
			INNER JOIN tourvisor_regions AS r ON s.regionId = r.id
            INNER JOIN tourvisor_countries AS c ON s.countryId = c.id AND active = 1
			WHERE s.queryDate > (NOW() - INTERVAL 1 MONTH)
			GROUP BY s.regionId
			ORDER BY COUNT(s.id) DESC
			LIMIT 6
		', \Phalcon\Db::FETCH_OBJ);

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
		if($this->currentCity->name_rod != $this->currentCity->departure->name_from)
		{
			$add = ' с вылетом из ' . $this->currentCity->departure->name_from;
		}

		$title = 'Туры из ' . $this->currentCity->name_rod . $add . ' на ';

		$this->view->setVars([
			'populars'			=> $popularItems,
			'popularCountries'	=> implode(', ', $popularCountries),
			'departures'		=> $departures,
			'params'			=> $this->params,
			'title'				=> $title,
			'page'				=> 'main'
		]);
	}

	public function agreementAction()
	{
		$this->view->disable();

		if($this->request->has('mobile')) {
			echo $this->simpleView->render('index/agreement');
		} else {
			$pdf = new \mPDF('BLANK', 'A4', 8, 'utf-8', 8, 8, 20, 20, 0, 0);

			$request = new \Backend\Models\Requests();

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
