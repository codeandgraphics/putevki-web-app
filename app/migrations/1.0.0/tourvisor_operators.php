<?php

use Phalcon\Db\Column;
use Phalcon\Db\Index;
use Phalcon\Db\Reference;
use Phalcon\Mvc\Model\Migration;

/**
 * Class TourvisorOperatorsMigration_100
 */
class TourvisorOperatorsMigration_100 extends Migration
{
    /**
     * Define the table structure
     *
     * @return void
     */
    public function morph()
    {
        $this->morphTable('tourvisor_operators', [
            'columns' => [
                new Column('id', [
                    'type' => Column::TYPE_INTEGER,
                    'default' => '0',
                    'notNull' => true,
                    'size' => 11,
                    'first' => true
                ]),
                new Column('name', [
                    'type' => Column::TYPE_VARCHAR,
                    'size' => 255,
                    'after' => 'id'
                ]),
                new Column('fullName', [
                    'type' => Column::TYPE_VARCHAR,
                    'size' => 255,
                    'after' => 'name'
                ]),
                new Column('russian', [
                    'type' => Column::TYPE_VARCHAR,
                    'size' => 255,
                    'after' => 'fullName'
                ]),
                new Column('onlineBooking', [
                    'type' => Column::TYPE_INTEGER,
                    'default' => '0',
                    'notNull' => true,
                    'size' => 1,
                    'after' => 'russian'
                ]),
                new Column('legal', [
                    'type' => Column::TYPE_VARCHAR,
                    'size' => 255,
                    'after' => 'onlineBooking'
                ]),
                new Column('guarantee', [
                    'type' => Column::TYPE_TEXT,
                    'size' => 1,
                    'after' => 'legal'
                ])
            ],
            'indexes' => [new Index('PRIMARY', ['id'], 'PRIMARY')],
            'options' => [
                'TABLE_TYPE' => 'BASE TABLE',
                'AUTO_INCREMENT' => '',
                'ENGINE' => 'InnoDB',
                'TABLE_COLLATION' => 'utf8_bin'
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
        $this->batchInsert('tourvisor_operators', [
            'id',
            'name',
            'fullName',
            'russian',
            'onlineBooking',
            'legal',
            'guarantee'
        ]);
    }
}
