<?php 

use Phalcon\Db\Column;
use Phalcon\Db\Index;
use Phalcon\Db\Reference;
use Phalcon\Mvc\Model\Migration;

/**
 * Class RequestsMigration_100
 */
class RequestsMigration_100 extends Migration
{
    /**
     * Define the table structure
     *
     * @return void
     */
    public function morph()
    {
        $this->morphTable('requests', [
                'columns' => [
                    new Column(
                        'id',
                        [
                            'type' => Column::TYPE_INTEGER,
                            'notNull' => true,
                            'autoIncrement' => true,
                            'size' => 11,
                            'first' => true
                        ]
                    ),
                    new Column(
                        'managerId',
                        [
                            'type' => Column::TYPE_INTEGER,
                            'size' => 11,
                            'after' => 'id'
                        ]
                    ),
                    new Column(
                        'subjectSurname',
                        [
                            'type' => Column::TYPE_VARCHAR,
                            'size' => 255,
                            'after' => 'managerId'
                        ]
                    ),
                    new Column(
                        'subjectName',
                        [
                            'type' => Column::TYPE_VARCHAR,
                            'size' => 255,
                            'after' => 'subjectSurname'
                        ]
                    ),
                    new Column(
                        'subjectPatronymic',
                        [
                            'type' => Column::TYPE_VARCHAR,
                            'size' => 255,
                            'after' => 'subjectName'
                        ]
                    ),
                    new Column(
                        'subjectAddress',
                        [
                            'type' => Column::TYPE_VARCHAR,
                            'size' => 255,
                            'after' => 'subjectPatronymic'
                        ]
                    ),
                    new Column(
                        'subjectPhone',
                        [
                            'type' => Column::TYPE_VARCHAR,
                            'size' => 50,
                            'after' => 'subjectAddress'
                        ]
                    ),
                    new Column(
                        'subjectEmail',
                        [
                            'type' => Column::TYPE_VARCHAR,
                            'size' => 255,
                            'after' => 'subjectPhone'
                        ]
                    ),
                    new Column(
                        'hotel',
                        [
                            'type' => Column::TYPE_TEXT,
                            'size' => 1,
                            'after' => 'subjectEmail'
                        ]
                    ),
                    new Column(
                        'flightsTo',
                        [
                            'type' => Column::TYPE_TEXT,
                            'size' => 1,
                            'after' => 'hotel'
                        ]
                    ),
                    new Column(
                        'flightsFrom',
                        [
                            'type' => Column::TYPE_TEXT,
                            'size' => 1,
                            'after' => 'flightsTo'
                        ]
                    ),
                    new Column(
                        'price',
                        [
                            'type' => Column::TYPE_DECIMAL,
                            'size' => 10,
                            'after' => 'flightsFrom'
                        ]
                    ),
                    new Column(
                        'departureId',
                        [
                            'type' => Column::TYPE_INTEGER,
                            'default' => "1",
                            'notNull' => true,
                            'size' => 11,
                            'after' => 'price'
                        ]
                    ),
                    new Column(
                        'tourOperatorId',
                        [
                            'type' => Column::TYPE_INTEGER,
                            'size' => 11,
                            'after' => 'departureId'
                        ]
                    ),
                    new Column(
                        'tourOperatorLink',
                        [
                            'type' => Column::TYPE_TEXT,
                            'size' => 1,
                            'after' => 'tourOperatorId'
                        ]
                    ),
                    new Column(
                        'requestStatusId',
                        [
                            'type' => Column::TYPE_INTEGER,
                            'default' => "0",
                            'notNull' => true,
                            'size' => 11,
                            'after' => 'tourOperatorLink'
                        ]
                    ),
                    new Column(
                        'creationDate',
                        [
                            'type' => Column::TYPE_DATETIME,
                            'size' => 1,
                            'after' => 'requestStatusId'
                        ]
                    ),
                    new Column(
                        'comment',
                        [
                            'type' => Column::TYPE_TEXT,
                            'size' => 1,
                            'after' => 'creationDate'
                        ]
                    ),
                    new Column(
                        'branchId',
                        [
                            'type' => Column::TYPE_INTEGER,
                            'size' => 11,
                            'after' => 'comment'
                        ]
                    ),
                    new Column(
                        'origin',
                        [
                            'type' => Column::TYPE_VARCHAR,
                            'default' => "web",
                            'notNull' => true,
                            'size' => 10,
                            'after' => 'branchId'
                        ]
                    ),
                    new Column(
                        'deleted',
                        [
                            'type' => Column::TYPE_VARCHAR,
                            'default' => "N",
                            'notNull' => true,
                            'size' => 1,
                            'after' => 'origin'
                        ]
                    )
                ],
                'indexes' => [
                    new Index('PRIMARY', ['id'], 'PRIMARY')
                ],
                'options' => [
                    'TABLE_TYPE' => 'BASE TABLE',
                    'AUTO_INCREMENT' => '61',
                    'ENGINE' => 'InnoDB',
                    'TABLE_COLLATION' => 'utf8_bin'
                ],
            ]
        );
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
