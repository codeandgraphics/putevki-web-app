<?php

use Phalcon\Db\Column;
use Phalcon\Db\Index;
use Phalcon\Db\Reference;
use Phalcon\Mvc\Model\Migration;

/**
 * Class CitiesMigration_101
 */
class CitiesMigration_101 extends Migration
{
    /**
     * Define the table structure
     *
     * @return void
     */
    public function morph()
    {
        $this->morphTable('cities', [
            'columns' => [
                new Column('id', [
                    'type' => Column::TYPE_INTEGER,
                    'notNull' => true,
                    'autoIncrement' => true,
                    'size' => 11,
                    'first' => true
                ]),
                new Column('name', [
                    'type' => Column::TYPE_VARCHAR,
                    'notNull' => true,
                    'size' => 100,
                    'after' => 'id'
                ]),
                new Column('nameRod', [
                    'type' => Column::TYPE_VARCHAR,
                    'default' => '',
                    'notNull' => true,
                    'size' => 255,
                    'after' => 'name'
                ]),
                new Column('nameDat', [
                    'type' => Column::TYPE_VARCHAR,
                    'default' => '',
                    'notNull' => true,
                    'size' => 255,
                    'after' => 'nameRod'
                ]),
                new Column('nameVin', [
                    'type' => Column::TYPE_VARCHAR,
                    'default' => '',
                    'notNull' => true,
                    'size' => 255,
                    'after' => 'nameDat'
                ]),
                new Column('nameTvo', [
                    'type' => Column::TYPE_VARCHAR,
                    'default' => '',
                    'notNull' => true,
                    'size' => 255,
                    'after' => 'nameVin'
                ]),
                new Column('namePre', [
                    'type' => Column::TYPE_VARCHAR,
                    'default' => '',
                    'notNull' => true,
                    'size' => 255,
                    'after' => 'nameTvo'
                ]),
                new Column('uri', [
                    'type' => Column::TYPE_VARCHAR,
                    'notNull' => true,
                    'size' => 100,
                    'after' => 'namePre'
                ]),
                new Column('lat', [
                    'type' => Column::TYPE_DECIMAL,
                    'notNull' => true,
                    'size' => 13,
                    'scale' => 10,
                    'after' => 'uri'
                ]),
                new Column('lon', [
                    'type' => Column::TYPE_DECIMAL,
                    'notNull' => true,
                    'size' => 13,
                    'scale' => 10,
                    'after' => 'lat'
                ]),
                new Column('zoom', [
                    'type' => Column::TYPE_INTEGER,
                    'notNull' => true,
                    'size' => 11,
                    'after' => 'lon'
                ]),
                new Column('flightCity', [
                    'type' => Column::TYPE_INTEGER,
                    'notNull' => true,
                    'size' => 11,
                    'after' => 'zoom'
                ]),
                new Column('phone', [
                    'type' => Column::TYPE_VARCHAR,
                    'notNull' => true,
                    'size' => 20,
                    'after' => 'flightCity'
                ]),
                new Column('main', [
                    'type' => Column::TYPE_INTEGER,
                    'notNull' => true,
                    'size' => 1,
                    'after' => 'phone'
                ]),
                new Column('active', [
                    'type' => Column::TYPE_INTEGER,
                    'default' => '1',
                    'notNull' => true,
                    'size' => 1,
                    'after' => 'main'
                ]),
                new Column('popularCountries', [
                    'type' => Column::TYPE_VARCHAR,
                    'size' => 255,
                    'after' => 'active'
                ]),
                new Column('metaDescription', [
                    'type' => Column::TYPE_TEXT,
                    'size' => 1,
                    'after' => 'popularCountries'
                ]),
                new Column('metaKeywords', [
                    'type' => Column::TYPE_VARCHAR,
                    'size' => 255,
                    'after' => 'metaDescription'
                ]),
                new Column('metaText', [
                    'type' => Column::TYPE_TEXT,
                    'size' => 1,
                    'after' => 'metaKeywords'
                ])
            ],
            'indexes' => [new Index('PRIMARY', ['id'], 'PRIMARY')],
            'options' => [
                'TABLE_TYPE' => 'BASE TABLE',
                'AUTO_INCREMENT' => '50',
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
        $this->batchInsert('cities', [
            'id',
            'name',
            'nameRod',
            'nameDat',
            'nameVin',
            'nameTvo',
            'namePre',
            'uri',
            'lat',
            'lon',
            'zoom',
            'flightCity',
            'phone',
            'main',
            'active',
            'popularCountries',
            'metaDescription',
            'metaKeywords',
            'metaText'
        ]);
    }

    /**
     * Reverse the migrations
     *
     * @return void
     */
    public function down()
    {
        $this->batchDelete('cities');
    }
}
