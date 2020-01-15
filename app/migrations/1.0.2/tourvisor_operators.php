<?php

use Phalcon\Db\Column;
use Phalcon\Db\Index;
use Phalcon\Db\Reference;
use Phalcon\Mvc\Model\Migration;

/**
 * Class TourvisorOperatorsMigration_102
 */
class TourvisorOperatorsMigration_102 extends Migration
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
                new Column('about', [
                    'type' => Column::TYPE_TEXT,
                    'size' => 1,
                    'after' => 'guarantee'
                ]),
                new Column('slug', [
                    'type' => Column::TYPE_VARCHAR,
                    'size' => 255,
                    'after' => 'about'
                ])
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
