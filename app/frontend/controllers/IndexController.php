<?php

namespace Frontend\Controllers;

use Backend\Controllers\EmailController;
use Backend\Models\Payments;
use Backend\Models\Requests;
use Frontend\Models\Params;
use Models\StoredQueries;
use Models\Tourvisor\Departures	as TourvisorDepartures;
use Models\Cities;
use Mobile_Detect;

class IndexController extends BaseController
{
	public function indexAction()
	{
		$popularRegions = StoredQueries::popularRegions(6);

		$popularItems = [];
		$popularCountries = [];

		foreach($popularRegions as $region)
		{
			$pop = new \stdClass();

			$pop->country = $region->country->name;
			$pop->region = $region->name;
			$pop->regionId = $region->id;
			$pop->countryId = $region->country->id;

			$from = $this->city->departure->name;
			$to = $region->country->name . '(' . $region->name . ')';

			$pop->url = '/search/'. $from . '/' . $to;

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
		if($this->city->nameRod !== $this->city->departure->nameFrom)
		{
			$add = ' с вылетом из ' . $this->city->departure->nameFrom;
		}

		$title = 'Путёвки из ' . $this->city->nameRod . $add . ' по лучшим ценам на ';

		$this->view->setVars([
			'populars'			=> $popularItems,
			'popularCountries'	=> implode(', ', $popularCountries),
			'departures'		=> $departures,
			'params'			=> $this->params,
			'title'				=> $title,
			'page'				=> 'main',
		]);
	}

	public function cityAction() {
	    $this->view->disable();
        $cityUri = $this->dispatcher->getParam('city');

        $params = Params::getInstance();
	    $city = Cities::findFirstByUri($cityUri);
	    if($city) {
            $params->city = (int) $city->id;
            $params->search->from = (int) $city->flightCity;
        }
        $params->store();

	    if(strpos($this->request->getHTTPReferer(), $this->url->get('')) !== false) {
		    $this->response->redirect($this->request->getHTTPReferer());
	    } else {
	    	return $this->response->redirect('');
	    }
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

		if($detect->isAndroidOS()) {
			$this->response->redirect($this->config->defaults->googlePlay);
		} else {
			$this->response->redirect($this->config->defaults->appStore);
		}

	}

	public function unitellerAction()
	{
		$this->view->setVars([
			'title' => 'Онлайн-оплата Uniteller',
			'page' => 'uniteller'
		]);
	}

    public function bestToursAction()
    {
        $this->view->setVars([
            'title' => 'Поиск туров от лучших туроператоров',
            'page' => 'best-tours'
        ]);
    }

    public function personalAction()
    {
        $this->view->setVars([
            'title' => 'Персональный подход к каждому клиенту и гарантия лучшей цены',
            'page' => 'personal'
        ]);
    }

	public function aboutAction() {
		$this->view->setVars([
			'title' => 'О нас –',
			'page' => 'about'
		]);
	}

	public function migrateAction() {
		$this->view->disable();

		/*$countries = Countries::find();

			$tx = $this->transactionManager->get();

			foreach ($countries as $country) {
				$about = $country->about;
				$country->setTransaction($tx);

				$imgPos = stripos($about, 'src="image');
				if ($imgPos !== false) {

					$newAbout = str_ireplace('="images/July', '="//static.putevki.ru/images/blog/July', $about);
					echo $about;
					echo PHP_EOL . PHP_EOL;
					echo $newAbout;
					echo PHP_EOL . PHP_EOL;

					$country->about = $newAbout;

					/*if ($country->save() === false) {
						$tx->rollback(
							'Cannot save'
						);
					}
				}
			}
		//$tx->commit();
		} catch (Failed $e) {
			echo 'Failed, reason: ', $e->getMessage();
		}*/
	}

	public function contactsAction() {
		$this->view->setVars([
			'title' => 'Контакты –',
			'page' => 'contacts'
		]);
	}

	public function robotsAction()
	{
		$this->view->disable();

		if($this->config->frontend->env === 'production') {
			echo 'User-agent: *' . PHP_EOL;
			echo 'Disallow: /admin/' . PHP_EOL;
			echo 'Disallow: /pay/' . PHP_EOL;
			echo 'Disallow: /api/' . PHP_EOL;
			echo 'Disallow: /ajax/' . PHP_EOL;
			echo 'Disallow: /exports/' . PHP_EOL;
			echo PHP_EOL;
			echo 'Crawl-delay: 20' . PHP_EOL;
			echo PHP_EOL;
			echo 'Host: ' . $this->url->get() . PHP_EOL;
		} else {
			echo 'User-agent: *' . PHP_EOL;
			echo 'Disallow: /' . PHP_EOL;
		}
	}

	public function testAction($type) {
		$this->view->disable();

		$requestId = 30;

		$emailController = new EmailController();

		switch ($type) {
			case 'online':
				$emailController->sendRequest('online', $requestId, true);
				break;
			case 'request':
				$emailController->sendRequest('request', $requestId, true);
				break;
			case 'admin':
				$emailController->sendAdminNotification($requestId, true);
				break;
			case 'manager':
				$emailController->sendManagerNotification($requestId, true);
				break;
			case 'branch':
				$emailController->sendBranchNotification($requestId, true);
				break;
			case 'findTour':
				$emailController->sendFindTour((object) ['name'=>'test', 'from'=>'asd'], true);
				break;
			case 'tourHelp':
				$emailController->sendTourHelp('phone', (object) ['name'=>'test', 'from'=>'asd'], true);
				break;
			case 'password':
				$emailController->sendPassword('test@test.com', 'asd', true);
				break;
			case 'payment':
				$emailController->sendManagerPaymentNotification(Payments::findFirstById(50), true);
				break;
			default:
				break;
		}

	}

	
}
