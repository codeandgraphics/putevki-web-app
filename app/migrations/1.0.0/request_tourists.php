<?php

use Phalcon\Db\Column;
use Phalcon\Db\Index;
use Phalcon\Db\Reference;
use Phalcon\Mvc\Model\Migration;

/**
 * Class RequestTouristsMigration_100
 */
class RequestTouristsMigration_100 extends Migration
{
    /**
     * Define the table structure
     *
     * @return void
     */
    public function morph()
    {
        $this->morphTable('request_tourists', [
            'columns' => [
                new Column('id', [
                    'type' => Column::TYPE_INTEGER,
                    'notNull' => true,
                    'autoIncrement' => true,
                    'size' => 11,
                    'first' => true
                ]),
                new Column('requestId', [
                    'type' => Column::TYPE_INTEGER,
                    'notNull' => true,
                    'size' => 11,
                    'after' => 'id'
                ]),
                new Column('touristId', [
                    'type' => Column::TYPE_INTEGER,
                    'notNull' => true,
                    'size' => 11,
                    'after' => 'requestId'
                ])
            ],
            'indexes' => [
                new Index('PRIMARY', ['id'], 'PRIMARY'),
                new Index('requestId', ['requestId', 'touristId'], 'UNIQUE')
            ],
            'options' => [
                'TABLE_TYPE' => 'BASE TABLE',
                'AUTO_INCREMENT' => '1',
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
}
