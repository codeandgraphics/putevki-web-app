<?php

use Phalcon\Db\Column;
use Phalcon\Db\Index;
use Phalcon\Db\Reference;
use Phalcon\Mvc\Model\Migration;

/**
 * Class RequestStatusesMigration_100
 */
class RequestStatusesMigration_100 extends Migration
{
    /**
     * Define the table structure
     *
     * @return void
     */
    public function morph()
    {
        $this->morphTable('request_statuses', [
            'columns' => [
                new Column('id', [
                    'type' => Column::TYPE_INTEGER,
                    'notNull' => true,
                    'autoIncrement' => true,
                    'size' => 11,
                    'first' => true
                ]),
                new Column('key', [
                    'type' => Column::TYPE_VARCHAR,
                    'notNull' => true,
                    'size' => 20,
                    'after' => 'id'
                ]),
                new Column('name', [
                    'type' => Column::TYPE_VARCHAR,
                    'notNull' => true,
                    'size' => 50,
                    'after' => 'key'
                ]),
                new Column('class', [
                    'type' => Column::TYPE_VARCHAR,
                    'notNull' => true,
                    'size' => 50,
                    'after' => 'name'
                ])
            ],
            'indexes' => [new Index('PRIMARY', ['id'], 'PRIMARY')],
            'options' => [
                'TABLE_TYPE' => 'BASE TABLE',
                'AUTO_INCREMENT' => '5',
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
        $this->batchInsert('request_statuses', ['id', 'key', 'name', 'class']);
    }
}
