<?php

namespace Backend\Controllers;

use Models\References\Countries;
use Phalcon\Db;
use Phalcon\Paginator\Adapter\Model			as PaginatorModel,
	Utils\DataParser,
	Utils\Yandex;

use Phalcon\Text;

class YandexController extends ControllerBase
{
	public function indexAction()
	{
		$this->view->disable();

		echo "<pre>";

		$ya = new Yandex();

		$ya->getData();
	}

	public function typeAction($type = 'countries')
	{
		$parser = new DataParser();
		$yandexType = 'Models\Yandex\\' . ucfirst($type);
		$tourvisorType = 'Models\Tourvisor\\' . ucfirst($type);

		$data = $yandexType::find([
			'order'	=> 'name'
		]);

		$count = $parser->parse($type, $data, $tourvisorType::find());

		$paginator = new PaginatorModel(
			array(
				"data"  => $data,
				"limit" => 50,
				"page"  => $this->request->get('page')
			)
		);

		$titles = [
			'countries'		=> 'Страны',
			'departures'	=> 'Города вылета',
			'operators'		=> 'Операторы'
		];

		$this->view->setVars([
			'type'	=> $type,
			'title'	=> $titles[$type],
			'page'	=> $paginator->getPaginate(),
			'count'	=> $count
		]);
		$this->view->pick('yandex/types');
	}

	public function hotelsAction($country = 1)
	{
		$countries = Countries::getReferenced();

		$reference = Countries::findFirst($country);

		$phql = "
			SELECT h.*
			FROM Models\Yandex\Regions AS r
			JOIN Models\Yandex\Hotels AS h ON h.region_id = r.id
			WHERE r.country_id = " . $reference->ya_ref_id . "
			ORDER BY h.name
		";

		$yandexHotels = $this->modelsManager->executeQuery($phql);

		$paginator = new PaginatorModel(
			array(
				"data"  => $yandexHotels,
				"limit" => 50,
				"page"  => $this->request->get('page'),
			)
		);

		$this->view->setVars([
			'countries'			=> $countries,
			'page'				=> $paginator->getPaginate(),
			'type'				=> 'hotels',
			'currentCountry'	=> $country
		]);
		$this->view->pick('yandex/hotels');
	}

	public function ajaxGetAction($type, $country = null)
	{
		$response = [];

		$term = $this->request->get('term', 'string');

		if($type == 'hotels')
		{
			$term = Text::upper($term);
			$query = 'name LIKE "%' . $term . '%" AND countryId = ' . $country;
		}
		else
		{
			$query = 'name LIKE "%' . $term . '%"';
		}

		$class = 'Models\Tourvisor\\' . ucfirst($type);

		$data = $class::find($query);

		foreach($data as $item)
		{
			if($type == 'hotels')
			{
				$response[] = [
					'id'		=> $item->id,
					'name'		=> $item->name,
					'region'	=> $item->region->name,
				];
			}
			else
			{
				$response[] = $item->toArray();
			}
		}

		echo json_encode($response);
		$this->view->disable();
	}

	public function ajaxAddReferenceAction($type)
	{
		$type = 'Models\References\\' . ucfirst($type);

		$reference = new $type;
		$reference->id = $this->request->getPost('id');
		$reference->ya_ref_id = $this->request->getPost('ya_ref_id');

		echo json_encode($reference->save());

		$this->view->disable();
	}

	public function ajaxDeleteReferenceAction($type)
	{
		$type = 'Models\References\\' . ucfirst($type);

		$reference = $type::findFirst('
			id = ' . $this->request->getPost('id') . '
			AND ya_ref_id = ' . $this->request->getPost('ya_ref_id'));

		echo json_encode($reference->delete());

		$this->view->disable();
	}
}