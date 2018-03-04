<?php 

use Phalcon\Db\Column;
use Phalcon\Db\Index;
use Phalcon\Db\Reference;
use Phalcon\Mvc\Model\Migration;

/**
 * Class CountriesMigration_100
 */
class CountriesMigration_100 extends Migration
{
    /**
     * Define the table structure
     *
     * @return void
     */
    public function morph()
    {
        $this->morphTable('countries', [
                'columns' => [
                    new Column(
                        'tourvisorId',
                        [
                            'type' => Column::TYPE_INTEGER,
                            'notNull' => true,
                            'size' => 11,
                            'first' => true
                        ]
                    ),
                    new Column(
                        'uri',
                        [
                            'type' => Column::TYPE_VARCHAR,
                            'size' => 100,
                            'after' => 'tourvisorId'
                        ]
                    ),
                    new Column(
                        'title',
                        [
                            'type' => Column::TYPE_VARCHAR,
                            'size' => 255,
                            'after' => 'uri'
                        ]
                    ),
                    new Column(
                        'preview',
                        [
                            'type' => Column::TYPE_VARCHAR,
                            'size' => 255,
                            'after' => 'title'
                        ]
                    ),
                    new Column(
                        'excerpt',
                        [
                            'type' => Column::TYPE_TEXT,
                            'size' => 1,
                            'after' => 'preview'
                        ]
                    ),
                    new Column(
                        'about',
                        [
                            'type' => Column::TYPE_TEXT,
                            'size' => 1,
                            'after' => 'excerpt'
                        ]
                    ),
                    new Column(
                        'metaKeywords',
                        [
                            'type' => Column::TYPE_VARCHAR,
                            'size' => 255,
                            'after' => 'about'
                        ]
                    ),
                    new Column(
                        'metaDescription',
                        [
                            'type' => Column::TYPE_TEXT,
                            'size' => 1,
                            'after' => 'metaKeywords'
                        ]
                    ),
                    new Column(
                        'popular',
                        [
                            'type' => Column::TYPE_INTEGER,
                            'default' => "0",
                            'notNull' => true,
                            'size' => 1,
                            'after' => 'metaDescription'
                        ]
                    ),
                    new Column(
                        'visa',
                        [
                            'type' => Column::TYPE_CHAR,
                            'default' => "0",
                            'notNull' => true,
                            'size' => 1,
                            'after' => 'popular'
                        ]
                    ),
                    new Column(
                        'active',
                        [
                            'type' => Column::TYPE_INTEGER,
                            'default' => "1",
                            'notNull' => true,
                            'size' => 1,
                            'after' => 'visa'
                        ]
                    )
                ],
                'indexes' => [
                    new Index('PRIMARY', ['tourvisorId'], 'PRIMARY'),
                    new Index('tourvisorId', ['tourvisorId'], 'UNIQUE')
                ],
                'options' => [
                    'TABLE_TYPE' => 'BASE TABLE',
                    'AUTO_INCREMENT' => '',
                    'ENGINE' => 'InnoDB',
                    'TABLE_COLLATION' => 'utf8_general_ci'
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

    /**
     * This method is called after the table was created
     *
     * @return void
     */
     public function afterCreateTable()
     {
        $this->batchInsert('countries', [
                'tourvisorId',
                'uri',
                'title',
                'preview',
                'excerpt',
                'about',
                'metaKeywords',
                'metaDescription',
                'popular',
                'visa',
                'active'
            ]
        );
     }
}
