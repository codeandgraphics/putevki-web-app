<?php

use Phalcon\CLI\Task;
use Models\Tourvisor;
use Models\Countries;
use Models\Regions;
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

            $items = TourvisorUtils::getMethod('list', $params)->lists->hotels
                ->hotel;

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
        $items = TourvisorUtils::getMethod('list', $params)->lists->countries
            ->country;

        $manager = $this->di->get('transactions');
        $transaction = $manager->get();

        $countries = [];

        foreach ($items as $item) {
            $tourvisorCountry = new Tourvisor\Countries();
            $tourvisorCountry->setTransaction($transaction);
            $tourvisorCountry->fromTourvisor($item);

            $tourvisorCountry->save();

            $country = new Countries();
            $country->tourvisorId = $tourvisorCountry->id;
            $country->active = 0;
            $country->create();

            $countries[] = $country;
        }

        $transaction->commit();

        $this->departureToCountriesAction($countries);

        echo PHP_EOL . 'Countries count: ' . count($items) . PHP_EOL;
    }

    public function departureToCountriesAction()
    {
        $departures = Tourvisor\Departures::find();

        $manager = $this->di->get('transactions');
        $transaction = $manager->get();

        foreach ($departures as $departure) {
            $params = array(
                'type' => 'country',
                'cndep' => $departure->id
            );

            $items = TourvisorUtils::getMethod('list', $params)->lists
                ->countries->country;

            if (sizeof($items) > 0) {
                foreach ($items as $item) {
                    $depToCountry = new Tourvisor\DeparturesToCountries();
                    $depToCountry->departureId = (int) $departure->id;
                    $depToCountry->countryId = (int) $item->id;

                    $depToCountry->setTransaction($transaction);
                    $depToCountry->save();
                }
            }
        }

        $transaction->commit();
    }

    public function regionsAction()
    {
        $params = array(
            'type' => 'region'
        );

        $items = TourvisorUtils::getMethod('list', $params)->lists->regions
            ->region;

        $manager = $this->di->get('transactions');
        $transaction = $manager->get();

        foreach ($items as $item) {
            $tourvisorRegion = new Tourvisor\Regions();
            $tourvisorRegion->setTransaction($transaction);
            $tourvisorRegion->fromTourvisor($item);
            $tourvisorRegion->save();

            $region = new Regions();
            $region->tourvisorId = $tourvisorRegion->id;
            $region->active = 0;
            $region->create();
        }

        $transaction->commit();

        echo PHP_EOL . 'Regions count: ' . count($items) . PHP_EOL;
    }

    public function departuresAction()
    {
        $params = array(
            'type' => 'departure'
        );

        $items = TourvisorUtils::getMethod('list', $params)->lists->departures
            ->departure;

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

        $items = TourvisorUtils::getMethod('list', $params)->lists->operators
            ->operator;

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
