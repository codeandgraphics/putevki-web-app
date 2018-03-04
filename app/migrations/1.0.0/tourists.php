<?php 

use Phalcon\Db\Column;
use Phalcon\Db\Index;
use Phalcon\Db\Reference;
use Phalcon\Mvc\Model\Migration;

/**
 * Class TouristsMigration_100
 */
class TouristsMigration_100 extends Migration
{
    /**
     * Define the table structure
     *
     * @return void
     */
    public function morph()
    {
        $this->morphTable('tourists', [
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
                        'passportName',
                        [
                            'type' => Column::TYPE_VARCHAR,
                            'default' => "",
                            'notNull' => true,
                            'size' => 255,
                            'after' => 'managerId'
                        ]
                    ),
                    new Column(
                        'passportSurname',
                        [
                            'type' => Column::TYPE_VARCHAR,
                            'default' => "",
                            'notNull' => true,
                            'size' => 255,
                            'after' => 'passportName'
                        ]
                    ),
                    new Column(
                        'passportNumber',
                        [
                            'type' => Column::TYPE_VARCHAR,
                            'default' => "",
                            'notNull' => true,
                            'size' => 20,
                            'after' => 'passportSurname'
                        ]
                    ),
                    new Column(
                        'passportEndDate',
                        [
                            'type' => Column::TYPE_DATE,
                            'notNull' => true,
                            'size' => 1,
                            'after' => 'passportNumber'
                        ]
                    ),
                    new Column(
                        'passportIssued',
                        [
                            'type' => Column::TYPE_TEXT,
                            'notNull' => true,
                            'size' => 1,
                            'after' => 'passportEndDate'
                        ]
                    ),
                    new Column(
                        'birthDate',
                        [
                            'type' => Column::TYPE_DATE,
                            'notNull' => true,
                            'size' => 1,
                            'after' => 'passportIssued'
                        ]
                    ),
                    new Column(
                        'nationality',
                        [
                            'type' => Column::TYPE_VARCHAR,
                            'size' => 255,
                            'after' => 'birthDate'
                        ]
                    ),
                    new Column(
                        'discount',
                        [
                            'type' => Column::TYPE_DECIMAL,
                            'size' => 10,
                            'after' => 'nationality'
                        ]
                    ),
                    new Column(
                        'gender',
                        [
                            'type' => Column::TYPE_CHAR,
                            'default' => "m",
                            'size' => 1,
                            'after' => 'discount'
                        ]
                    ),
                    new Column(
                        'phone',
                        [
                            'type' => Column::TYPE_VARCHAR,
                            'size' => 255,
                            'after' => 'gender'
                        ]
                    ),
                    new Column(
                        'email',
                        [
                            'type' => Column::TYPE_VARCHAR,
                            'size' => 255,
                            'after' => 'phone'
                        ]
                    ),
                    new Column(
                        'creationDate',
                        [
                            'type' => Column::TYPE_DATETIME,
                            'size' => 1,
                            'after' => 'email'
                        ]
                    ),
                    new Column(
                        'deleted',
                        [
                            'type' => Column::TYPE_CHAR,
                            'notNull' => true,
                            'size' => 1,
                            'after' => 'creationDate'
                        ]
                    )
                ],
                'indexes' => [
                    new Index('PRIMARY', ['id'], 'PRIMARY')
                ],
                'options' => [
                    'TABLE_TYPE' => 'BASE TABLE',
                    'AUTO_INCREMENT' => '1',
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
