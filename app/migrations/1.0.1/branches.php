<?php

use Phalcon\Db\Column;
use Phalcon\Db\Index;
use Phalcon\Db\Reference;
use Phalcon\Mvc\Model\Migration;

/**
 * Class BranchesMigration_101
 */
class BranchesMigration_101 extends Migration
{
    /**
     * Define the table structure
     *
     * @return void
     */
    public function morph()
    {
        $this->morphTable('branches', [
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
                    'size' => 255,
                    'after' => 'id'
                ]),
                new Column('address', [
                    'type' => Column::TYPE_VARCHAR,
                    'size' => 255,
                    'after' => 'name'
                ]),
                new Column('addressDetails', [
                    'type' => Column::TYPE_TEXT,
                    'size' => 1,
                    'after' => 'address'
                ]),
                new Column('timetable', [
                    'type' => Column::TYPE_VARCHAR,
                    'size' => 255,
                    'after' => 'addressDetails'
                ]),
                new Column('phone', [
                    'type' => Column::TYPE_VARCHAR,
                    'size' => 255,
                    'after' => 'timetable'
                ]),
                new Column('site', [
                    'type' => Column::TYPE_VARCHAR,
                    'size' => 255,
                    'after' => 'phone'
                ]),
                new Column('email', [
                    'type' => Column::TYPE_VARCHAR,
                    'notNull' => true,
                    'size' => 255,
                    'after' => 'site'
                ]),
                new Column('additionalEmails', [
                    'type' => Column::TYPE_TEXT,
                    'size' => 1,
                    'after' => 'email'
                ]),
                new Column('lat', [
                    'type' => Column::TYPE_DECIMAL,
                    'size' => 13,
                    'scale' => 10,
                    'after' => 'additionalEmails'
                ]),
                new Column('lon', [
                    'type' => Column::TYPE_DECIMAL,
                    'size' => 13,
                    'scale' => 10,
                    'after' => 'lat'
                ]),
                new Column('cityId', [
                    'type' => Column::TYPE_INTEGER,
                    'notNull' => true,
                    'size' => 11,
                    'after' => 'lon'
                ]),
                new Column('main', [
                    'type' => Column::TYPE_INTEGER,
                    'default' => '0',
                    'notNull' => true,
                    'size' => 1,
                    'after' => 'cityId'
                ]),
                new Column('active', [
                    'type' => Column::TYPE_INTEGER,
                    'default' => '1',
                    'notNull' => true,
                    'size' => 1,
                    'after' => 'main'
                ]),
                new Column('metaDescription', [
                    'type' => Column::TYPE_TEXT,
                    'size' => 1,
                    'after' => 'active'
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
                ]),
                new Column('managerId', [
                    'type' => Column::TYPE_INTEGER,
                    'size' => 11,
                    'after' => 'metaText'
                ]),
                new Column('managerPassword', [
                    'type' => Column::TYPE_VARCHAR,
                    'size' => 255,
                    'after' => 'managerId'
                ])
            ],
            'indexes' => [new Index('PRIMARY', ['id'], 'PRIMARY')],
            'options' => [
                'TABLE_TYPE' => 'BASE TABLE',
                'AUTO_INCREMENT' => '85',
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
        $this->batchInsert('branches', [
            'id',
            'name',
            'address',
            'addressDetails',
            'timetable',
            'phone',
            'site',
            'email',
            'additionalEmails',
            'lat',
            'lon',
            'cityId',
            'main',
            'active',
            'metaDescription',
            'metaKeywords',
            'metaText',
            'managerId',
            'managerPassword'
        ]);
    }

    /**
     * Reverse the migrations
     *
     * @return void
     */
    public function down()
    {
        $this->batchDelete('branches');
    }
}
