<?php

use Phalcon\Db\Column;
use Phalcon\Db\Index;
use Phalcon\Db\Reference;
use Phalcon\Mvc\Model\Migration;

/**
 * Class TourvisorDeparturesToCountriesMigration_101
 */
class TourvisorDeparturesToCountriesMigration_101 extends Migration
{
    /**
     * Define the table structure
     *
     * @return void
     */
    public function morph()
    {
        $this->morphTable('tourvisor_departures_to_countries', [
            'columns' => [
                new Column('departureId', [
                    'type' => Column::TYPE_INTEGER,
                    'notNull' => true,
                    'size' => 11,
                    'first' => true
                ]),
                new Column('countryId', [
                    'type' => Column::TYPE_INTEGER,
                    'notNull' => true,
                    'size' => 11,
                    'after' => 'departureId'
                ])
            ],
            'indexes' => [
                new Index('PRIMARY', ['departureId', 'countryId'], 'PRIMARY')
            ],
            'options' => [
                'TABLE_TYPE' => 'BASE TABLE',
                'AUTO_INCREMENT' => '',
                'ENGINE' => 'InnoDB',
                'TABLE_COLLATION' => 'utf8_general_ci'
            ]
        ]);
    }

    /**
     * Run the migrations
     *
     * @return void
     */
    public function up()
    {
        $this->batchInsert('tourvisor_departures_to_countries', [
            'departureId',
            'countryId'
        ]);
    }

    /**
     * Reverse the migrations
     *
     * @return void
     */
    public function down()
    {
        $this->batchDelete('tourvisor_departures_to_countries');
    }
}
