<?php

namespace Frontend\Models;


use Phalcon\Di;
use Phalcon\Mvc\Model,
	Phalcon\Mvc\Model\Behavior\Timestampable,
	Models\Tourvisor,
	Utils\Text;

class SearchQueries extends Model
{
	const DELAY_TIME = 600;
	
	public $id;
	public $tourvisorId;
	public $departureId = null;
	public $countryId = null;
	public $regionId = null;
	public $hotelId = null;
	public $operatorId = null;
	public $date = null;
	public $date_range = 0;
	public $nights = 0;
	public $nights_range = 0;
	public $adults = 0;
	public $kids = 0;
	public $starsId = 1;
	public $mealId = 1;
	public $queryDate;

	private $_isHotelQuery = false;
	
	public function initialize()
	{	
		$this->belongsTo('countryId', 'Models\Tourvisor\Countries', 'id', array(
            'alias' => 'country'
        ));
        
		$this->belongsTo('regionId', 'Models\Tourvisor\Regions', 'id', array(
            'alias' => 'region'
        ));
		
		$this->belongsTo('departureId', 'Models\Tourvisor\Departures', 'id', array(
            'alias' => 'departure'
        ));

		$this->belongsTo('hotelId', 'Models\Tourvisor\Hotels', 'id', array(
			'alias' => 'hotel'
		));

		$this->belongsTo('operatorId', 'Models\Tourvisor\Operators', 'id', array(
			'alias' => 'operator'
		));
		
		$this->belongsTo('starsId', 'Models\Tourvisor\Stars', 'id', array(
            'alias' => 'stars'
        ));
		
		$this->belongsTo('mealId', 'Models\Tourvisor\Meals', 'id', array(
            'alias' => 'meal'
        ));
		
		$this->addBehavior(new Timestampable(
			array(
				'beforeValidationOnCreate'	=> array(
					'field'			=> 'queryDate',
					'format'		=> 'Y-m-d H:i:s'
				)
			)
		));
	}
	
	public function fillFromParams($params)
	{
		if(is_numeric($params->from))
		{
			$departure = Tourvisor\Departures::findFirst("id='$params->from'");
		}
		else
		{
			$departure = Tourvisor\Departures::findFirst("name='$params->from'");
		}

		$this->departureId = 1;

		if($departure->id)
		{
			$this->departureId = $departure->id;
		}

		if(is_numeric($params->country))
		{
			$this->countryId = (int) $params->country;

			if(isset($params->region) && is_numeric($params->region))
				$this->regionId = $params->region;
		}
		else
		{
			preg_match_all('/\((.*?)\)/', $params->where, $matches);

			$regionName = false;

			if($matches[1])
			{
				$regionName = $matches[1][0] ?: false;
			}

			if($regionName)
			{
				$region = Tourvisor\Regions::findFirst("name='$regionName'");
				if($region)
				{
					$this->countryId = (int) $region->countryId;
					$this->regionId = (int) $region->id;
				}
			}
			else
			{
				$countryName = $params->where;
				$country = Tourvisor\Countries::findFirst("name='$countryName'");
				if($country)
				{
					$this->countryId = (int) $country->id;
					$this->regionId = null;
				}
			}
		}

		if(isset($params->hotel) && $params->hotel)
		{
			$this->hotelId = $params->hotel;
			$this->_isHotelQuery = true;
		}

		if(isset($params->operator) && $params->operator)
		{
			$this->operatorId = $params->operator;
		}
		
		if(strpos($params->date,'~') === 0)
		{
			$this->date_range = 1;
		}
		$params->date = str_replace('~', '', $params->date);
		$this->date = date('Y-m-d',strtotime($params->date));
				
		if(strpos($params->nights,'~') === 0)
		{
			$this->nights_range = 1;
		}
		$nights = str_replace('~', '', $params->nights);
		$this->nights = (int) $nights;
		
		$this->adults = (int) $params->adults;
		$this->kids = str_replace(' ', '+', $params->kids);

		if(!$this->kids)
		{
			$this->kids = 0;
		}

		$this->starsId = $params->stars;
		$this->mealId = $params->meal;
	}

	public function run()
	{
		$region = $this->regionId ? "regionId = '$this->regionId'" : 'regionId IS NULL';
		$hotel = $this->hotelId ? "hotelId = '$this->hotelId'" : 'hotelId IS NULL';
		$operator = $this->operatorId ? "operatorId = '$this->operatorId'" : 'operatorId IS NULL';

		$query = "departureId = '$this->departureId' AND ".
			"countryId = '$this->countryId' AND " . $region . ' AND '. $hotel . ' AND ' . $operator . ' AND ' .
			"date = '$this->date' AND date_range = '$this->date_range' AND ".
			"nights = '$this->nights' AND nights_range = '$this->nights_range' AND ".
			"adults = '$this->adults' AND kids = '$this->kids' AND ".
			"starsId = '$this->starsId' AND mealId = '$this->mealId'";

		$existed = self::findFirst(
			$query
		);

		if(!$existed || (time() - strtotime($existed->queryDate)) >= self::DELAY_TIME)
		{
			$result = \Utils\Tourvisor::getMethod('search', $this->buildTourvisorQuery());

			$this->tourvisorId = $result->result->requestid;
			$this->queryDate = date('Y-m-d H:i:s');
			$this->save();
		}
		else
		{
			$this->assign($existed->toArray());
		}

		$this->addLastQueries();

		setcookie('params', serialize($this->toArray()), time() + $this->getDI()->get('config')->frontend->cookie_remember_timeout, '/');
	}
	
	public function buildTourvisorQuery()
	{
		$query = array(
			'departure'		=> $this->departureId,
			'country'		=> $this->countryId,
			'adults'		=> $this->adults,
			'rating'		=> 3,
			'stars'			=> $this->starsId,
			'starsbetter'	=> 1,
			'meal'			=> $this->mealId,
			'mealbetter'	=> 1
		);
		
		$date = \DateTime::createFromFormat('Y-m-d',$this->date);
		
		if($this->regionId)
		{
			$query['regions'] = $this->regionId;
		}
		
		if($this->date_range)
		{
			$date->modify('-1 day');
			$query['datefrom'] = $date->format('d.m.Y');
			$date->modify('+2 day');
			$query['dateto'] = $date->format('d.m.Y');
		}
		else
		{
			$query['datefrom'] = $date->format('d.m.Y');
			$query['dateto'] = $date->format('d.m.Y');
		}
		
		if($this->nights_range)
		{
			$query['nightsfrom'] = $this->nights - 1;
			$query['nightsto'] = $this->nights + 1;
		}
		else
		{
			$query['nightsfrom'] = $this->nights;
			$query['nightsto'] = $this->nights;
		}
		
		if($this->kids)
		{
			$kids = explode('+',$this->kids);
			$query['child'] = count($kids);
			foreach($kids as $key=>$value)
			{
				$query['childage'.($key+1)] = $value;
			}
		}

		if($this->hotelId)
		{
			$query['hotels'] = $this->hotelId;

			unset(
				$query['meal'],
				$query['stars'],
				$query['starsbetter'],
				$query['rating']
			);
		}

		if($this->operatorId)
		{
			$query['operators'] = $this->operatorId;
		}
		
		return $query;
	}
	
	public function buildQueryString()
	{
		$queryString = $this->departure->name;
		$queryString .= '/' . $this->country->name;

		if($this->regionId)
		{
			$queryString .= '(' . $this->region->name . ')';
		}

		if($this->hotelId)
		{
			$hotelName = str_replace(array(' ', '&'), array('_', 'AND'), $this->hotel->name);
			$queryString .= '/' . $hotelName . '-' . $this->hotel->id;
		}

		$queryString .= $this->date_range ? '/~' : '/';
		$queryString .= implode('.', array_reverse(explode('-',$this->date))); //Хз что быстрее, strtotime или это

		$queryString .= $this->nights_range ? '/~' : '/';
		$queryString .= $this->nights;

		$queryString .= '/' . $this->adults;
		$queryString .= '/' . $this->kids;

		$queryString .= '/' . $this->starsId;
		$queryString .= '/' . $this->mealId;
			
		return $queryString;
	}

	public static function buildQueryStringFromParams($params)
	{
		$queryString = '';
		if(property_exists($params, 'hotel'))
		{
			$queryString .= 'hotel/';
		}
		$queryString .= $params->departure;
		$queryString .= '/' . $params->country;

		if($params->region)
		{
			$queryString .= '(' . $params->region . ')';
		}

		if(property_exists($params, 'hotel'))
		{
			$queryString .= '/' . $params->hotel;
		}

		$queryString .= $params->date_range ? '/~' : '/';
		$queryString .= implode('.', array_reverse(explode('-',$params->date))); //Хз что быстрее, strtotime или это

		$queryString .= $params->nights_range ? '/~' : '/';
		$queryString .= $params->nights;

		$queryString .= '/' . $params->adults;
		$queryString .= '/' . $params->kids;

		$queryString .= '/' . $params->starsId;
		$queryString .= '/' . $params->mealId;

		return $queryString;
	}
	
	
	public static function checkParams()
	{
		if(array_key_exists('params', $_COOKIE) && $_COOKIE['params'])
		{
			$params = (object) unserialize($_COOKIE['params']);
			
			if(strtotime($params->date) < strtotime('+1 day')) 
			{
				$params->date = date('Y-m-d', strtotime('+1 day'));
			}
		}
		else
		{
			$config = Di::getDefault()->get('config');
			$params = new \stdClass();
			$params->departureId = $config->frontend->defaultFlightCity;
			$params->countryId = '';
			$params->regionId = '';
			$params->nights = 7;
			$params->nights_range = true;
			$params->date = date('Y-m-d', strtotime('+3 day'));
			$params->date_range = true;
			$params->adults = 2;
			$params->kids = 0;
			$params->starsId = 2;
			$params->mealId = 2;

			if(array_key_exists('flight_city', $_COOKIE))
			{
				$params->departureId = $_COOKIE['flight_city'];
			}
		}
		
		return $params;
	}

	public function addLastQueries()
	{
		if(array_key_exists('lastQueries',$_COOKIE))
		{
			$lastQueries = unserialize($_COOKIE['lastQueries']);
			$lastQueries[] = $this->buildHumanizedQuery();

			if(count($lastQueries) > 3)
			{
				array_shift($lastQueries);
			}
		}
		else
		{
			$lastQueries = [ $this->buildHumanizedQuery() ];
		}

		setcookie('lastQueries', serialize($lastQueries), time() + $this->getDI()->get('config')->frontend->cookie_remember_timeout, '/');
	}
		
	public function buildTitle()
	{
		$title = '';
		if($this->region)
		{
			$title .= $this->region->name.', '.$this->country->name;
		}
		else
		{
			$title .= $this->country->name;
		}
		
		return $title;
	}

	public function buildHumanizedQuery()
	{
		$queryString = $this->departure->name . ' — ' ;

		if($this->countryId)
			$queryString .=  $this->country->name;

		if($this->regionId)
			$queryString .= ' (' . $this->region->name . ')';

		if($this->hotelId)
			$queryString .= ' ' . $this->hotel->name . '';

		$queryString .= ', ' . implode('.', array_reverse(explode('-',$this->date))); //Хз что быстрее, strtotime или это
		$queryString .= ($this->date_range) ? ' (±2 дня)' : '';

		$queryString .= ', ' . Text::humanize('nights', $this->nights);
		$queryString .= ($this->nights_range) ? ' (±2 ночи)' : '';

		$queryString .= ', ' . $this->adults . ' ' . Text::humanize('adults', $this->adults);

		$kidsCount = count(explode('+',$this->kids));

		if($kidsCount > 0)
			$queryString .= ', ' . $kidsCount . ' ' . Text::humanize('kids', $kidsCount);

		$queryString .= ', ' . $this->starsId . ' звезд и выше';
		$queryString .= ', ' . $this->meal->name;

		return $queryString;

	}

	public function isHotelQuery()
	{
		return $this->_isHotelQuery;
	}
}