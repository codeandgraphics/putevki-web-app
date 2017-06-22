<?php

use \Models\Tourvisor;

class TourvisorTask extends \Phalcon\CLI\Task
{

	public function mainAction()
	{
		$this->mealsAction();
		$this->starsAction();
		$this->departuresAction();
		$this->operatorsAction();

		$this->countriesAction();
		$this->regionsAction();

		$this->hotelsAction();
	}

	public function hotelsAction(){

		$countries = Tourvisor\Countries::find();

		$manager = $this->di->get('transactions');
		$transaction = $manager->get();

		$allHotels = 0;

		foreach($countries as $country){
			$params = array(
				'type'	=> 'hotel',
				'hotcountry'	=> $country->id
			);

			$apiHotels = \Utils\Tourvisor::getMethod('list', $params)->lists->hotels->hotel;

			foreach($apiHotels as $apiHotel)
			{
				$hotel = new Tourvisor\Hotels();
				$hotel->setTransaction($transaction);

				$hotel->id = $apiHotel->id;
				$hotel->name = $apiHotel->name;
				$hotel->starsId = $apiHotel->stars;
				$hotel->regionId = $apiHotel->region;
				$hotel->countryId = $country->id;
				$hotel->rating = $apiHotel->rating;

				$hotel->active = isset($apiHotel->active) ? $apiHotel->active : 0;
				$hotel->relax = isset($apiHotel->relax) ? $apiHotel->relax : 0;
				$hotel->family = isset($apiHotel->family) ? $apiHotel->family : 0;
				$hotel->health = isset($apiHotel->health) ? $apiHotel->health : 0;
				$hotel->city = isset($apiHotel->city) ? $apiHotel->city : 0;
				$hotel->beach = isset($apiHotel->beach) ? $apiHotel->beach : 0;
				$hotel->deluxe = isset($apiHotel->deluxe) ? $apiHotel->deluxe : 0;

				$hotel->save();
				$allHotels++;
			}
		}

		$transaction->commit();

		echo "\nHotels count: " . $allHotels . " \n";
	}

	public function countriesAction()
	{
		$params = array(
			'type'	=> 'country'
		);
		$apiCountries = \Utils\Tourvisor::getMethod('list', $params)->lists->countries->country;

		$manager = $this->di->get('transactions');
		$transaction = $manager->get();

		foreach($apiCountries as $apiCountry)
		{
			$country = new Tourvisor\Countries();
			$country->setTransaction($transaction);
			$country->id = $apiCountry->id;
			$country->name = $apiCountry->name;

			$country->save();

			$enabledCountries[] = $country->name;
		}

		$transaction->commit();

		echo "\nCountries count: " . count($apiCountries) . " \n";
	}

	public function regionsAction()
	{

		$params = array(
			'type'	=> 'region'
		);
		$apiRegions = \Utils\Tourvisor::getMethod('list', $params)->lists->regions->region;

		$manager = $this->di->get('transactions');
		$transaction = $manager->get();

		foreach($apiRegions as $apiRegion)
		{
			$region = new Tourvisor\Regions();
			$region->setTransaction($transaction);
			$region->id = $apiRegion->id;
			$region->name = $apiRegion->name;
			$region->countryId = $apiRegion->country;

			$region->save();
		}

		$transaction->commit();

		echo "\nRegions count: " . count($apiRegions) . " \n";
	}

	public function departuresAction()
	{

		$params = array(
			'type'	=> 'departure'
		);
		$apiDepartures = \Utils\Tourvisor::getMethod('list', $params)->lists->departures->departure;

		$manager = $this->di->get('transactions');
		$transaction = $manager->get();

		foreach($apiDepartures as $apiDeparture)
		{
			$departure = new Tourvisor\Departures();
			$departure->setTransaction($transaction);
			$departure->id = $apiDeparture->id;
			$departure->name = $apiDeparture->name;
			$departure->name_from = $apiDeparture->namefrom;

			$departure->save();
		}

		$transaction->commit();

		echo "\nDepartures count: " . count($apiDepartures) . " \n";
	}

	public function operatorsAction()
	{

		$params = array(
			'type'	=> 'operator'
		);
		$apiOperators = \Utils\Tourvisor::getMethod('list', $params)->lists->operators->operator;

		$manager = $this->di->get('transactions');
		$transaction = $manager->get();

		foreach($apiOperators as $apiOperator)
		{
			$operator = new Tourvisor\Operators();
			$operator->setTransaction($transaction);
			$operator->id = $apiOperator->id;
			$operator->name = $apiOperator->name;
			$operator->fullname = $apiOperator->fullname;
			$operator->russian = $apiOperator->russian;
			$operator->onlinebooking = $apiOperator->onlinebooking;

			$operator->save();
		}

		$transaction->commit();

		echo "\nOperators count: " . count($apiOperators) . " \n";
	}

	public function mealsAction()
	{
		$params = array(
			'type'	=> 'meal'
		);
		$apiArray = \Utils\Tourvisor::getMethod('list', $params)->lists->meals->meal;

		$manager = $this->di->get('transactions');
		$transaction = $manager->get();

		foreach($apiArray as $apiItem)
		{
			$item = new Tourvisor\Meals();
			$item->setTransaction($transaction);
			$item->id = $apiItem->id;
			$item->name = $apiItem->name;
			$item->fullname = $apiItem->fullname;
			$item->russian = $apiItem->russian;
			$item->russianfull = $apiItem->russianfull;

			$item->save();
		}

		$transaction->commit();

		echo "\nMeals count: " . count($apiArray) . " \n";
	}

	public function starsAction()
	{
		$params = array(
			'type'	=> 'stars'
		);
		$apiArray = \Utils\Tourvisor::getMethod('list', $params)->lists->stars->star;

		$manager = $this->di->get('transactions');
		$transaction = $manager->get();

		foreach($apiArray as $apiItem)
		{
			$item = new Tourvisor\Stars();
			$item->setTransaction($transaction);
			$item->id = $apiItem->id;
			$item->name = $apiItem->name;

			$item->save();
		}

		$transaction->commit();

		echo "\nStars count: " . count($apiArray) . " \n";
	}

}