<?php

use Phalcon\Db\Column;
use Phalcon\Db\Index;
use Phalcon\Db\Reference;
use Phalcon\Mvc\Model\Migration;

/**
 * Class TourvisorDeparturesMigration_100
 */
class TourvisorDeparturesMigration_100 extends Migration
{
    /**
     * Define the table structure
     *
     * @return void
     */
    public function morph()
    {
        $this->morphTable('tourvisor_departures', [
            'columns' => [
                new Column('id', [
                    'type' => Column::TYPE_INTEGER,
                    'notNull' => true,
                    'size' => 11,
                    'first' => true
                ]),
                new Column('name', [
                    'type' => Column::TYPE_VARCHAR,
                    'notNull' => true,
                    'size' => 255,
                    'after' => 'id'
                ]),
                new Column('nameFrom', [
                    'type' => Column::TYPE_VARCHAR,
                    'default' => '',
                    'notNull' => true,
                    'size' => 255,
                    'after' => 'name'
                ]),
                new Column('popular', [
                    'type' => Column::TYPE_INTEGER,
                    'default' => '0',
                    'notNull' => true,
                    'size' => 1,
                    'after' => 'nameFrom'
                ])
            ],
            'indexes' => [new Index('PRIMARY', ['id'], 'PRIMARY')],
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
    }

    /**
     * Reverse the migrations
     *
     * @return void
     */
    public function down()
    {
    }

    /**
     * This method is called after the table was created
     *
     * @return void
     */
    public function afterCreateTable()
    {
        $this->batchInsert('tourvisor_departures', [
            'id',
            'name',
            'nameFrom',
            'popular'
        ]);
    }
}
