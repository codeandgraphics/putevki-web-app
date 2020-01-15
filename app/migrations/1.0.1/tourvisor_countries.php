<?php

use Phalcon\Db\Column;
use Phalcon\Db\Index;
use Phalcon\Db\Reference;
use Phalcon\Mvc\Model\Migration;

/**
 * Class TourvisorCountriesMigration_101
 */
class TourvisorCountriesMigration_101 extends Migration
{
    /**
     * Define the table structure
     *
     * @return void
     */
    public function morph()
    {
        $this->morphTable('tourvisor_countries', [
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
                new Column('active', [
                    'type' => Column::TYPE_INTEGER,
                    'default' => '0',
                    'notNull' => true,
                    'size' => 1,
                    'after' => 'name'
                ]),
                new Column('popular', [
                    'type' => Column::TYPE_INTEGER,
                    'default' => '0',
                    'notNull' => true,
                    'size' => 1,
                    'after' => 'active'
                ]),
                new Column('visa', [
                    'type' => Column::TYPE_CHAR,
                    'default' => '0',
                    'notNull' => true,
                    'size' => 1,
                    'after' => 'popular'
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
        $this->batchInsert('tourvisor_countries', [
            'id',
            'name',
            'active',
            'popular',
            'visa'
        ]);
    }

    /**
     * Reverse the migrations
     *
     * @return void
     */
    public function down()
    {
        $this->batchDelete('tourvisor_countries');
    }
}
