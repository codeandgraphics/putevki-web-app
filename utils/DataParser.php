<?php

namespace Utils;

use Models\References\Hotels;
use Phalcon\Db,
    Phalcon\Di,
    Phalcon\Mvc\Model\Transaction\Manager	as TransactionManager;

class DataParser
{
    private $db;
    private $txManager;

    public function __construct()
    {
        $this->db = Di::getDefault()->get('db');

        $this->txManager = new TransactionManager();
    }

    public function getReferences($type)
    {
        $data = $this->db->fetchAll('
            SELECT type.id, tourvisor.name, type.ya_ref_id, yandex.name AS ya_ref_name
            FROM reference_' . $type . ' AS type
            INNER JOIN tourvisor_'.$type.' AS tourvisor ON type.id = tourvisor.id
            INNER JOIN yandex_'.$type.' AS yandex ON type.ya_ref_id = yandex.id
        ', Db::FETCH_OBJ);

        return ($data) ? $data : null;
    }

    public function parse($type, $yandexData, $tourvisorData){

		$type = '\Models\References\\' . ucfirst($type);
        $result = [
            'yandex'    => 0,
            'tourvisor' => 0,
            'parsed'    => 0
        ];

        $result['yandex']      = sizeof($yandexData);
        $result['tourvisor']   = sizeof($tourvisorData);

		$tx = $this->txManager->get();

        foreach($yandexData as $yandexItem)
		{
			foreach ($tourvisorData as $tourvisorItem)
			{
				if ($this->filterName($type, $tourvisorItem->name) == $this->filterName($type, $yandexItem->name))
				{
					$result['parsed']++;

					$reference = new $type;
					$reference->id = $tourvisorItem->id;
					$reference->ya_ref_id = $yandexItem->id;

					$reference->setTransaction($tx);
					$reference->create();

					break;
				}
			}
		}

		if(!$tx->commit())
		{
			var_dump($tx->getMessages());
		}

        return $result;
    }

    public function parseHotels($yandexData, $tourvisorData)
    {
        $result = [
            'yandex'	=> 0,
			'tourvisor'	=> 0,
            'count'		=> 0,
			'references'=> []
        ];

        $result['yandex']		= sizeof($yandexData);
		$result['tourvisor']	= sizeof($tourvisorData);

        $yandexHotelsToFilter = [];
        $tourvisorHotelsToFilter = [];

        foreach($yandexData as $yandexHotel)
		{
            $yandexHotelsToFilter[$this->filterName('hotels',$yandexHotel->name)] = $yandexHotel;
        }

        foreach($tourvisorData as $tourvisorHotel)
		{
            $tourvisorHotelsToFilter[$this->filterName('hotels',$tourvisorHotel->name)] = $tourvisorHotel;
        }

		$tx = $this->txManager->get();

        foreach($yandexHotelsToFilter as $yandexName => $yandexHotel)
		{
            if(isset($tourvisorHotelsToFilter[$yandexName]))
			{
				$reference = new Hotels();
				$reference->id = $tourvisorHotelsToFilter[$yandexName]->id;
				$reference->ya_ref_id = $yandexHotel->id;

				$reference->setTransaction($tx);
				$reference->save();

				$result['references'][$yandexHotel->id] = $tourvisorHotelsToFilter[$yandexName];

                $result['count']++;
            }
        }

		if(!$tx->commit())
		{
			var_dump($tx->getMessages());
		}

        return $result;
    }

    public function filterName($type, $name)
    {
        if($type == 'hotels')
        {
            $name = mb_strtoupper($name, 'UTF-8');

            $patterns = [];
            $patterns[0] = '/HOTEL/';
            $patterns[1] = '/ПАНСИОНАТ/';
            $patterns[2] = '/ОТЕЛЬ/';
            $patterns[3] = '/САНАТОРИЙ/';
            $patterns[4] = '/\s*\([^)]*\)/';
            $patterns[5] = '/s+/';

            $name = preg_replace($patterns, '', $name);

            $name = trim($name);
        }

        return $name;
    }

}