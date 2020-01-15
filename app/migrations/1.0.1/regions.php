<?php

use Phalcon\Db\Column;
use Phalcon\Db\Index;
use Phalcon\Db\Reference;
use Phalcon\Mvc\Model\Migration;

/**
 * Class RegionsMigration_101
 */
class RegionsMigration_101 extends Migration
{
    /**
     * Define the table structure
     *
     * @return void
     */
    public function morph()
    {
        $this->morphTable('regions', [
            'columns' => [
                new Column('tourvisorId', [
                    'type' => Column::TYPE_INTEGER,
                    'notNull' => true,
                    'size' => 11,
                    'first' => true
                ]),
                new Column('uri', [
                    'type' => Column::TYPE_VARCHAR,
                    'size' => 100,
                    'after' => 'tourvisorId'
                ]),
                new Column('title', [
                    'type' => Column::TYPE_VARCHAR,
                    'size' => 255,
                    'after' => 'uri'
                ]),
                new Column('preview', [
                    'type' => Column::TYPE_VARCHAR,
                    'size' => 255,
                    'after' => 'title'
                ]),
                new Column('about', [
                    'type' => Column::TYPE_TEXT,
                    'size' => 1,
                    'after' => 'preview'
                ]),
                new Column('metaKeywords', [
                    'type' => Column::TYPE_VARCHAR,
                    'size' => 255,
                    'after' => 'about'
                ]),
                new Column('metaDescription', [
                    'type' => Column::TYPE_TEXT,
                    'size' => 1,
                    'after' => 'metaKeywords'
                ]),
                new Column('popular', [
                    'type' => Column::TYPE_INTEGER,
                    'default' => '0',
                    'size' => 1,
                    'after' => 'metaDescription'
                ]),
                new Column('active', [
                    'type' => Column::TYPE_INTEGER,
                    'default' => '1',
                    'notNull' => true,
                    'size' => 1,
                    'after' => 'popular'
                ]),
                new Column('hasInfo', [
                    'type' => Column::TYPE_INTEGER,
                    'default' => '0',
                    'size' => 1,
                    'after' => 'active'
                ])
            ],
            'indexes' => [new Index('tourvisorId', ['tourvisorId'], 'UNIQUE')],
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
        $this->batchInsert('regions', [
            'tourvisorId',
            'uri',
            'title',
            'preview',
            'about',
            'metaKeywords',
            'metaDescription',
            'popular',
            'active',
            'hasInfo'
        ]);
    }

    /**
     * Reverse the migrations
     *
     * @return void
     */
    public function down()
    {
        $this->batchDelete('regions');
    }
}
