<?php

namespace Utils;

use Models\Yandex\Hotels;
use Models\Yandex\Regions;
use Phalcon\Cache\Backend\File				as Cache,
	Phalcon\Cache\Frontend\Data				as CacheData,
	Phalcon\Mvc\Model\Transaction\Manager	as TransactionManager,
	Phalcon\Di;

use Models\Yandex\Countries;
use Models\Yandex\Departures;
use Models\Yandex\Operators;

class Yandex
{
	private $endpoint;
	private $token;
	private $txManager;

	protected $_cache;

	public function __construct(){

		$cacheData = new CacheData(
			array(
				"lifetime" => 172800
			)
		);

		$this->_cache = new Cache(
			$cacheData,
			array(
				"cacheDir" => "../app/cache/"
			)
		);

		$this->token = "I6tBaQzOfgLQjlWNjlLE";
		$this->endpoint = "https://ota.travel.yandex.ru/";

		$this->hmac_key = 'qy9Ei3c7WfMxGnKG2uJt';

		$this->txManager = new TransactionManager();
	}

	public function checkHMAC($query,$remote_hmac){
		return $remote_hmac === hash_hmac('sha256', $query, $this->hmac_key);
	}

	public function parseTour($data){

		$tour = new \stdClass;

		$country = Countries::findFirst($data->ya_country);

		$tour->country = (object) array(
			'id'	=> $country->id,
			'name'	=> $country->name,

			'yandex' => (object) array(
				'id'	=> $country->id,
				'name'	=> $country->name
			)
		);

		$departure = Departures::findFirst($data->ya_departure_city);

		$tour->departure = (object) array(
			'id'	=> $departure->id,
			'name'	=> $departure->name,

			'yandex' => (object) array(
				'id'	=> $departure->id,
				'name'	=> $departure->name
			)
		);

		$operator = Operators::findFirst($data->ya_operator);

		$tour->operator = (object) array(
			'id'	=> $operator->id,
			'name'	=> $operator->name,

			'yandex' => (object) array(
				'id'	=> $operator->id,
				'name'	=> $operator->name
			)
		);

		$hotel = Hotels::findFirst($data->ya_hotel);

		$region = Regions::findFirst($hotel->region_id);

		$tour->hotel = (object) array(
			'id'	=> $hotel->id,
			'name'	=> $hotel->name,
			'room'	=> $data->room->name,
			'meal'	=> \Utils\Text::humanize('meal', $data->meal->code),
			'stars'	=> $hotel->stars,
			'region'=> $region->name,

			'yandex' => (object) array(
				'id'	=> $hotel->id,
				'name'	=> $hotel->name
			)
		);

		$tour->start_time = strtotime($data->check_in_date);
		$tour->end_time = $tour->start_time + ($data->nights * 60 * 60 * 24);
		$tour->nights = $data->nights;

		$tour->flights = $data->flights;

		$tour->tourists = $data->tourist_group;

		$tour->booking_url = $data->booking_url;

		$tour->fuel = $data->fuel_charge;

		$tour->visa = $data->visa;

		$tour->price = $data->price;

		$tour->infant_price = $data->infant_price;

		$tour->final_price = $tour->fuel + $tour->price;

		$tour->final_price += $tour->infant_price;

		if(!empty($tour->tourists->kids_ages)){
			$tour->tourists->infants += sizeof($tour->tourists->kids_ages);
		}

		return $tour;
	}

	private function filterString($string)
	{
		return str_replace('"', '', $string);
	}

	public function getData()
	{
		$parsed = new \stdClass();
		$parsed->countries = [];
		$parsed->departures = [];
		$parsed->operators = [];
		$parsed->regions = [];
		$parsed->hotels = [];

		$regionsData = $this->query('regions');
		$this->processRegions($regionsData, $parsed);

		$parsed->operators = $this->query('operators');

		$hotels = $this->query('hotels');

		$hotels = explode("\n", $hotels);

		array_shift($hotels);

		foreach ($hotels as $i => $hotel)
		{
			$parsed->hotels[] = explode("\t", $hotel);
		}

		$tx = $this->txManager->get();

		Countries::find()->delete();

		foreach($parsed->countries as $item)
		{
			$country = new Countries();
			$country->id = $item->id;
			$country->name = $item->name;
			$country->name_en = $item->name_en;
			$country->setTransaction($tx);
			$country->save();
		}

		$tx->commit();

		$tx = $this->txManager->get();

		Regions::find()->delete();

		foreach($parsed->regions as $item)
		{
			$region = new Regions();
			$region->id = $item->id;
			$region->name = $item->name;
			$region->name_en = $item->name_en;
			$region->country_id = $item->country_id;
			$region->setTransaction($tx);
			$region->save();
		}

		$tx->commit();

		$tx = $this->txManager->get();

		Departures::find()->delete();

		foreach($parsed->departures as $item)
		{
			$departure = new Departures();
			$departure->id = $item->id;
			$departure->name = $item->name;
			$departure->name_en = $item->name_en;
			$departure->setTransaction($tx);
			$departure->save();
		}

		$tx->commit();

		$tx = $this->txManager->get();

		Operators::find()->delete();

		foreach ($parsed->operators as $item)
		{
			$operator = new Operators();
			$operator->id = $item->id;
			$operator->name = $item->name;
			$operator->setTransaction($tx);
			$operator->save();
		}

		$tx->commit();

		$tx = $this->txManager->get();

		foreach ($parsed->hotels as $item)
		{
			if(sizeof($item) > 1)
			{
				$hotel = new Hotels();
				$hotel->id = (int) $item[0];
				$hotel->name = $item[1];
				$hotel->name_en = $item[2];
				$hotel->stars = (int) $item[3];
				$hotel->region_id = (int) $item[4];
				$hotel->setTransaction($tx);
				$hotel->save();
			}
		}

		$tx->commit();
	}

	public function processRegions($data, &$parsed)
	{
		foreach($data->children as $child)
		{
			if (property_exists($child, 'is_country') && $child->is_country)
			{
				$country = new \stdClass();
				$country->id = $child->id;
				$country->name = $child->name->ru;
				$country->name_en = $child->name->en;

				$parsed->countries[] = $country;

				if (property_exists($child, 'children') && !empty($child->children))
				{
					$this->processCountryChilds($child, $child->id, $parsed);
				}
			}
			elseif (property_exists($child, 'children') && !empty($child->children))
			{
				$this->processRegions($child, $parsed);
			}
		}
	}

	public function processCountryChilds($data, $country_id, &$parsed)
	{
		foreach($data->children as $child)
		{
			if(property_exists($child, 'is_destination') && $child->is_destination)
			{
				$region = new \stdClass();
				$region->id = $child->id;
				$region->country_id = $country_id;
				$region->name = $child->name->ru;
				$region->name_en = $child->name->en;
				$parsed->regions[] = $region;
			}
			if(property_exists($child, 'is_departure') && $child->is_departure)
			{
				$departure = new \stdClass();
				$departure->id = $child->id;
				$departure->name = $child->name->ru;
				$departure->name_en = $child->name->en;
				$parsed->departures[] = $departure;
			}
			if(property_exists($child, 'children') && !empty($child->children))
			{
				$this->processCountryChilds($child, $country_id, $parsed);
			}
		}
	}

	public function query($method)
	{
		$cacheKey = 'yandex_cache.query.' . $method;
		$content = $this->_cache->get($cacheKey);

		if($content === null)
		{
			$request_headers = array();
			$request_headers[] = 'Authorization: Token token="' . $this->token . '"';

			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $this->endpoint . $method);
			curl_setopt($ch, CURLOPT_HEADER, FALSE);
			curl_setopt($ch, CURLOPT_NOBODY, FALSE);
			curl_setopt($ch, CURLOPT_HTTPHEADER, $request_headers);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
			$content = curl_exec($ch);
			$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
			curl_close($ch);

			if($httpCode == 200)
			{
				$this->_cache->save($cacheKey, $content);
			}
			else
			{
				$content = false;
			}
		}

		if($method === 'hotels' || !$content)
			return $content;
		else
			return json_decode(gzdecode($content));
	}

}

?>