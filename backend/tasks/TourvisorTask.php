<?php

use Phalcon\CLI\Task;
use Models\Tourvisor;
use Utils\Tourvisor as TourvisorUtils;

class TourvisorTask extends Task
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

	public function hotelsAction()
	{
		$countries = Tourvisor\Countries::find();

		$manager = $this->di->get('transactions');
		$transaction = $manager->get();

		$allHotels = 0;

		foreach ($countries as $country) {
			$params = array(
				'type' => 'hotel',
				'hotcountry' => $country->id
			);

			$items = TourvisorUtils::getMethod('list', $params)->lists->hotels->hotel;

			foreach ($items as $item) {
				$hotel = new Tourvisor\Hotels();
				$hotel->setTransaction($transaction);
				$hotel->countryId = $country->id;
				$hotel->fromTourvisor($item);
				$hotel->save();
				$allHotels++;
			}
		}

		$transaction->commit();

		echo PHP_EOL . 'Hotels count: ' . $allHotels . PHP_EOL;
	}

	public function countriesAction()
	{
		$params = array(
			'type' => 'country'
		);
		$items = TourvisorUtils::getMethod('list', $params)->lists->countries->country;

		$manager = $this->di->get('transactions');
		$transaction = $manager->get();

		foreach ($items as $item) {
			$country = new Tourvisor\Countries();
			$country->setTransaction($transaction);
			$country->fromTourvisor($item);

			$country->save();

			$enabledCountries[] = $country->name;
		}

		$transaction->commit();

		echo PHP_EOL . 'Countries count: ' . count($items) . PHP_EOL;
	}

	public function regionsAction()
	{
		$params = array(
			'type' => 'region'
		);

		$items = TourvisorUtils::getMethod('list', $params)->lists->regions->region;

		$manager = $this->di->get('transactions');
		$transaction = $manager->get();

		foreach ($items as $item) {
			$region = new Tourvisor\Regions();
			$region->setTransaction($transaction);
			$region->fromTourvisor($item);
			$region->save();
		}

		$transaction->commit();

		echo PHP_EOL . 'Regions count: ' . count($items) . PHP_EOL;
	}

	public function departuresAction()
	{
		$params = array(
			'type' => 'departure'
		);

		$items = TourvisorUtils::getMethod('list', $params)->lists->departures->departure;

		$manager = $this->di->get('transactions');
		$transaction = $manager->get();

		foreach ($items as $item) {
			$departure = new Tourvisor\Departures();
			$departure->setTransaction($transaction);
			$departure->fromTourvisor($item);
			$departure->save();
		}

		$transaction->commit();

		echo PHP_EOL . 'Departures count: ' . count($items) . PHP_EOL;
	}

	public function operatorsAction()
	{
		$params = array(
			'type' => 'operator'
		);

		$items = TourvisorUtils::getMethod('list', $params)->lists->operators->operator;

		$manager = $this->di->get('transactions');
		$transaction = $manager->get();

		foreach ($items as $item) {
			$operator = new Tourvisor\Operators();
			$operator->setTransaction($transaction);
			$operator->fromTourvisor($item);
			$operator->save();
		}

		$transaction->commit();

		echo PHP_EOL . 'Operators count: ' . count($items) . PHP_EOL;
	}

	public function mealsAction()
	{
		$params = array(
			'type' => 'meal'
		);

		$items = TourvisorUtils::getMethod('list', $params)->lists->meals->meal;

		$manager = $this->di->get('transactions');
		$transaction = $manager->get();

		foreach ($items as $item) {
			$meal = new Tourvisor\Meals();
			$meal->setTransaction($transaction);
			$meal->fromTourvisor($item);
			$meal->save();
		}

		$transaction->commit();

		echo PHP_EOL . 'Meals count: ' . count($items) . PHP_EOL;
	}

	public function starsAction()
	{
		$params = array(
			'type' => 'stars'
		);

		$items = TourvisorUtils::getMethod('list', $params)->lists->stars->star;

		$manager = $this->di->get('transactions');
		$transaction = $manager->get();

		foreach ($items as $item) {
			$star = new Tourvisor\Stars();
			$star->setTransaction($transaction);
			$star->fromTourvisor($item);
			$star->save();
		}

		$transaction->commit();

		echo PHP_EOL . 'Stars count: ' . count($items) . PHP_EOL;
	}

}